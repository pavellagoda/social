<?php

/**
 * Auth utils
 *
 * @author Pasha
 */
//var_dump (Zend_Auth::getInstance()->getIdentity());
class App_Admin_Auth
{
	
	/**
	 * Error messages
	 * @var array
	 */
	private static $_aErrorMessages = array();

	/**
	 * Instance of a custom session storage class.
	 *
	 * @var object
	 */
	private static $_oAuthStorage = null;

	//----------------------------------------------------------------------------------------------------
	

	protected static function _getStorage()
	{
		if (self::$_oAuthStorage === null)
		{
			self::$_oAuthStorage = new Zend_Auth_Storage_Session('Zend_Auth_Admin');
		}

		return self::$_oAuthStorage;
	}

	/**
	 * Checks if user is logged in
	 * @return bool
	 */
	public static function isLogged ()
	{
		$oAuth = Zend_Auth::getInstance()->setStorage(self::_getStorage());

		if ($oAuth->hasIdentity())
		{
			$aIdentity = $oAuth->getIdentity();

			if (! empty($aIdentity['Admin_id']))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	//----------------------------------------------------------------------------------------------------
	

	/**
	 * Returns logged user
	 * 
	 * @return models_Admins|null
	 */
	public static function getLoggedAdmin ()
	{
		if (self::isLogged())
		{
			$aIdentity = Zend_Auth::getInstance()->setStorage(self::_getStorage())->getIdentity();
			
			return models_AdminsMapper::findById($aIdentity['Admin_id']);
		}
		
		return null;
	}

	//----------------------------------------------------------------------------------------------------
	

	/**
	 * Returns logged user id
	 * @return int|null
	 */
	public static function getLoggedAdminId ()
	{
		if (self::isLogged())
		{
			$aIdentity = Zend_Auth::getInstance()->setStorage(self::_getStorage())->getIdentity();
			return $aIdentity['Admin_id'];
		}
		else
		{
			return null;
		}
	}

	//----------------------------------------------------------------------------------------------------
	

	/**
	 * Log in by auth hash
	 *
	 * @param string $hash
	 */

	//----------------------------------------------------------------------------------------------------
	

	/**
	 * Performs admin logout
	 *
	 */
	public static function logout ()
	{
		if (self::isLogged())
		{
			$oAuth = Zend_Auth::getInstance();
			$oAuth->setStorage(self::_getStorage());

			$oAuth->getStorage()->clear();
			$oAuth->clearIdentity();
		}
	}
	
	public static function setLogged ($iAdminId)
	{
		if (! self::isLogged())
		{
			$aIdentity = Zend_Auth::getInstance()->setStorage(self::_getStorage())->getIdentity();
			$aIdentity['Admin_id'] = $iAdminId;
			
			$oAuthStorage = Zend_Auth::getInstance()->setStorage(self::_getStorage())->getStorage();
			$oAuthStorage->write($aIdentity);
		}
	}

	//----------------------------------------------------------------------------------------------------
	

	/**
	 * Performs admin login
	 *
	 * @param Zend_Form $oForm
	 * @param array $aPost
	 * @param bool $bRememberMe
	 * @throws Lemar_Exception|Lemar_AccountExpiredException
	 * @return bool
	 */
	public static function doLogin (Zend_Form $oForm, $aPost, $bRememberMe)
	{
		if ($oForm->isValid($aPost))
		{
			$oDbAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
			$oAuthAdapter = new Zend_Auth_Adapter_DbTable($oDbAdapter);
			$oAuthAdapter	->setTableName('admins')
						 	->setIdentityColumn('email')
							->setCredentialColumn('password');
			
			$oAuthAdapter->setIdentity($oForm->getValue('email'))
						 ->setCredential($oForm->getValue('password'));
		
			$oResult = $oAuthAdapter->authenticate();
			
			if (! $oResult->isValid())
			{
				/** Auth doesn't have own translator. Admin translators directly */
				/* @var $oTranslate Zend_Translate */
				$oTranslate = Zend_Registry::get('Zend_Translate');
				
				$aAuthErrorMessages = array();
				
				$sControlIndex = (Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID ==
						 $oResult->getCode()) ? 'password' : 'email';
				
				$aAuthErrorMessages[0][] = 'admin';
				$aAuthErrorMessages[0][] = 'Login information is invalid';
				$aAuthErrorMessages[0][] = 'error';
				
				self::setErrorMessages($aAuthErrorMessages);
				
				//throw new Lemar_Exception('Login failed!');
				
				return false;
			}
			else
			{
				// Store auth object in storage (in session by default)
				$stdClassAdmin = $oAuthAdapter->getResultRowObject();
				
//				if ($stdClassAdmin->status != models_Admins::STATUS_ACTIVE)
//				{
//					$aAuthErrorMessages[0][] = 'user';
//					$aAuthErrorMessages[0][] = 'User is not active';
//					$aAuthErrorMessages[0][] = 'error';
//
//					self::setErrorMessages($aAuthErrorMessages);
//
//					return false;
//				}
					
				
				App_Admin_Auth::setLogged($stdClassAdmin->id);
				
				$oZendSession = Zend_Registry::getInstance()->get('Zend_Session_Namespace');
				$oZendSession->errorMessage = '';
				
				if ($bRememberMe)
				{
					Zend_Session::rememberMe();
				}
			}
		}
		else
		{
			$aAuthErrorMessages[0][] = 'user';
			$aAuthErrorMessages[0][] = 'Admin ' . $aPost['email'] . ' is not registered';
			$aAuthErrorMessages[0][] = 'error';
			
			self::setErrorMessages($aAuthErrorMessages);
			
			//throw new Lemar_Exception('Validation failed!');
			
			return false;
		}
		
		return true;
	}

	//----------------------------------------------------------------------------------------------------
	

	public static function getErrorMessages ()
	{
		return self::$_aErrorMessages;
	}

	//----------------------------------------------------------------------------------------------------
	

	public static function setErrorMessages ($aErrorMessages)
	{
		self::$_aErrorMessages = $aErrorMessages;
	}

	//----------------------------------------------------------------------------------------------------	
}
