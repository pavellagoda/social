<?php

/**
 * User utils
 *
 * @author pavel
 */
class App_User {

    /**
     * Error messages
     * @var array
     */
    private static $_aErrorMessages = array();

    /**
     * 
     * @var models_Users
     */
    protected static $loggedUser = null;

    /**
     * Checks if user is logged in
     * @return bool
     */
    public static function isLogged() {
        $oAuth = Zend_Auth::getInstance();

        if (App_Auth::hasIdentity()) {
            $aIdentity = App_Auth::getIdentity();

            if (!empty($aIdentity)) {
                return true;
            }
            else
                return false;
        }
        else {
            return false;
        }
    }

    //----------------------------------------------------------------------------------------------------


    public static function register($data) {

        $oZendSession = Zend_Registry::getInstance()->get('Zend_Session_Namespace');

        if (isset($oZendSession->guestLogin)) {
            $idUser = $oZendSession->guestLogin;
            $user->id = $idUser;
            models_UserMapper::update($idUser, $user->toArray(), models_UserMapper::$_dbTable);
            unset($oZendSession->guestLogin);
        } else {
            $user = new Model_Users();
            $user->fullname = $data['fullname'];
            $user->username = $data['username'];
            $user->email = $data['email'];
            $user->date = date('Y-m-d H:i:s');
            $user->password = App_StringGenerator::encodePassword($data['password']);
            $user->save();
            $idUser = $user->id;
        }
        self::setLogged($idUser);
        return true;
    }

    /**
     * Returns logged user
     * 
     * @return Model_Users|null
     */
    public static function getLoggedUser() {
        if (self::$loggedUser) {
            return self::$loggedUser;
        } else {
            if (self::isLogged()) {
                $aIdentity = Zend_Auth::getInstance()->getIdentity();
                self::$loggedUser = App_ServiceLocator::UserMapper()->find($aIdentity);
                return self::$loggedUser;
            }
        }
        return null;
    }

    //----------------------------------------------------------------------------------------------------

    /**
     * Returns logged user id
     * @return int|null
     */
    public static function getLoggedUserId() {
        if (self::isLogged()) {
            $aIdentity = Zend_Auth::getInstance()->getIdentity();
            return $aIdentity;
        } else {
            return null;
        }
    }

    //----------------------------------------------------------------------------------------------------

    /**
     * Log in by auth hash
     *
     * @param string $hash
     */
    public static function loginByHash($hash) {
        $user = models_UsersMapper::findByHash($hash);
        if (null != $user) {
            self::setLogged($user->id);
            return true;
        }
        return false;
    }

    //----------------------------------------------------------------------------------------------------

    /**
     * Sets user logged in
     *
     * @param int $iUserId
     */
    public static function setLogged($iUserId) {
        if (!self::isLogged()) {
            $aIdentity = Zend_Auth::getInstance()->getIdentity();
            $aIdentity = $iUserId;
            $oAuthStorage = Zend_Auth::getInstance()->getStorage();
            $oAuthStorage->write($aIdentity);
        }
    }

    //----------------------------------------------------------------------------------------------------

    /**
     * Performs user logout
     *
     */
    public static function logout() {
        if (self::isLogged()) {
            Zend_Auth::getInstance()->clearIdentity();
        }
    }

    //----------------------------------------------------------------------------------------------------

    /**
     * Performs user login
     *
     * @param Zend_Form $oForm
     * @param array $aPost
     * @param bool $bRememberMe
     * @throws Lemar_Exception|Lemar_AccountExpiredException
     * @return bool
     */
    public static function doLogin(Zend_Form $oForm, $aPost, $bRememberMe) {
        if ($oForm->isValid($aPost)) {
            $oResult = App_Auth::authenticate($oForm->getValue('UserName'), $oForm->getValue('Password'));
            if (!$oResult->isValid()) {
                /** Auth doesn't have own translator. Uses translators directly */
                /* @var $oTranslate Zend_Translate */
//				$oTranslate = Zend_Registry::get('Zend_Translate');

                $aAuthErrorMessages = array();

                $sControlIndex = (Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID ==
                        $oResult->getCode()) ? 'password' : 'email';

                $aAuthErrorMessages[0][] = 'user';
                $aAuthErrorMessages[0][] = 'Login information is invalid';
                $aAuthErrorMessages[0][] = 'error';

                self::setErrorMessages($aAuthErrorMessages);

                //throw new Lemar_Exception('Login failed!');

                return false;
            } else {
                // Store auth object in storage (in session by default)
                $id = $oResult->getIdentity();

//                App_User::setLogged($id);

                $oZendSession = Zend_Registry::getInstance()->get(
                        'Zend_Session_Namespace');
                $oZendSession->errorMessage = '';

                if ($bRememberMe) {
                    Zend_Session::rememberMe();
                }
            }
        } else {
            $aAuthErrorMessages[0][] = 'user';
            $aAuthErrorMessages[0][] = 'User ' . $aPost['email'] . ' is not registered';
            $aAuthErrorMessages[0][] = 'error';

            self::setErrorMessages($aAuthErrorMessages);

            //throw new Lemar_Exception('Validation failed!');

            return false;
        }

        return true;
    }

    //----------------------------------------------------------------------------------------------------


    public static function getErrorMessages() {
        return self::$_aErrorMessages;
    }

    //----------------------------------------------------------------------------------------------------


    public static function setErrorMessages($aErrorMessages) {
        self::$_aErrorMessages = $aErrorMessages;
    }

    //----------------------------------------------------------------------------------------------------	

    /**
     * Activates user's account
     * 
     * @author andrey
     * 
     * @param $iUserId
     * @return bool
     */
    public static function activate($iUserId) {

        if (models_UsersMapper::edit($iUserId, array('status' => models_Users::STATUS_ACTIVE))) {
            return true;
        }

        return false;
    }

    /**
     * Checks if user subscribed
     * 
     * @author andrey
     * 
     * @param models_Teachers $teacher
     * @return bool
     */
    public static function isSubscribed(models_Teachers $teacher) {
        if (time() <= strtotime($teacher->getSubscribedTo()))
            return true;
        else
            return false;
    }

//-----------------------------------------------------------------------------
    public static function registerStudent($userData) {

        $activationCode = md5(time() . ' ' . rand(1, 999999));

        $user = new models_Users();

        $user->setFirstName($userData['first_name']);
        $user->setLastName($userData['last_name']);
        $user->setSex($userData['sex']);
        $user->setEmail($userData['email']);
        $user->setPassword($userData['password']);
        $user->setPhone($userData['phone']);
        $user->setBirthdate(Lemar_Date::convertFormats($userData['birth_date'], Lemar_Date::SITE_DATE, Lemar_Date::MYSQL_DATE));
        $user->setStatus(models_Users::STATUS_REGISTERED);
        $user->setRole(models_Users::ROLE_STUDENT);

        $idUser = models_UsersMapper::save($user);

        $user->setId($idUser);

        $userKey = new models_UserKey();

        $userKey->setIdUser($idUser);
        $userKey->setKey($activationCode);
        $userKey->setType(models_UserKey::ACTIVATION);

        models_UserKeyMapper::save($userKey);

        $student = new models_Students();

        $student->setIdUser($idUser);
        $student->setCity($userData['city']);
        $student->setDistrict($userData['district']);
        $student->setOccupation($userData['occupation']);
        $student->setAboutMe($userData['about_me']);

        models_StudentsMapper::save($student);

        models_MessagesMapper::editBySenderEmail($user->getEmail(), array('sender_id' => $user->getId()));

        Lemar_Notifier::sendActivateAccountMessage(models_UsersMapper::findById($idUser), $activationCode);
    }

//------------------------------------------------------------------------------
    /**
     * 
     * @param Zend_Form $form
     * @return models_Users
     */
    public static function registerTeacher(Zend_Form $form) {
        $userData = $form->getValues();
        $activationCode = md5(time() . ' ' . rand(1, 999999));

        $user = new models_Users();

        $user->setFirstName($userData['first_name']);
        $user->setLastName($userData['last_name']);
        $user->setSex($userData['sex']);
        $user->setEmail($userData['email']);
        $user->setPassword($userData['password']);
        $user->setPhone($userData['phone']);
        $user->setBirthdate(Lemar_Date::convertFormats($userData['birth_date'], Lemar_Date::SITE_DATE, Lemar_Date::MYSQL_DATE));
        $user->setStatus(models_Users::STATUS_REGISTERED);
        $user->setRole(models_Users::ROLE_TEACHER);

        $idUser = models_UsersMapper::save($user);

        $user->setId($idUser);

        $userKey = new models_UserKey();

        $userKey->setIdUser($idUser);
        $userKey->setKey($activationCode);
        $userKey->setType(models_UserKey::ACTIVATION);

        models_UserKeyMapper::save($userKey);

        $teacher = new models_Teachers();

        $teacher->setIdUser($idUser);
        $teacher->setCity($userData['city']);
        $teacher->setDistrict($userData['district']);
        $teacher->setAdditionalInfo($userData['additional_info']);
        $teacher->setAddress($userData['address']);
        $teacher->setBachelorUniversity($userData['bachelor_university']);
        $teacher->setGroupLessons($userData['group_lessons']);
        $teacher->setHomeworkJobs($userData['homework_jobs']);
        $teacher->setTranslationJobs($userData['translation_jobs']);
        $teacher->setOpenToWorkoffers($userData['open_to_workoffers']);
        $teacher->setSmsSupport(0);

        if (isset($userData['custom_occupation'])) {
            $teacher->setOccupation($userData['occupation'], $userData['custom_occupation']);
        } else {
            $teacher->setOccupation($userData['occupation']);
        }

        if (isset($userData['graduate_university']))
            $teacher->setGraduateUniversity($userData['graduate_university']);
        if (isset($userData['doctorate_university']))
            $teacher->setDoctorateUniversity($userData['doctorate_university']);
        if (isset($userData['lesson_location']))
            $teacher->setLessonLocation(implode(',', $userData['lesson_location']));

        $photoFileName = self::uploadTeacherPhoto($form->getElement('photo'), $user);

        if ($photoFileName)
            $teacher->setPhotoFile($photoFileName);

        $iTeacherId = models_TeachersMapper::save($teacher);

        $teacher->setId($iTeacherId);

        self::makePhotoPreview($teacher);

        foreach ($userData['lesson_districts'] as $iDistrictId) {
            $lessonDistricts = new models_LessonDistricts();

            $lessonDistricts->setDistrictId($iDistrictId);
            $lessonDistricts->setTeacherId($iTeacherId);

            models_LessonDistrictsMapper::save($lessonDistricts);
        }

        foreach ($userData['lesson_types'] as $iLessonTypeId) {
            $teacherLessonType = new models_TeachersLessonTypes();

            $teacherLessonType->setIdLessonType($iLessonTypeId);
            $teacherLessonType->setIdTeacher($iTeacherId);
            $teacherLessonType->setYearsOfExperience(
                    $userData['lesson_years_of_experience'][$iLessonTypeId]);

            models_TeachersLessonTypesMapper::save($teacherLessonType);

            foreach ($userData['lesson_levels'][$iLessonTypeId] as $i => $sLessonLevel) {
                $lessonLevel = new models_LessonLevels();

                $lessonLevel->setLessonTypeId($teacherLessonType->getIdLessonType());
                $lessonLevel->setLevel($sLessonLevel);
                $lessonLevel->setPrice($userData['lesson_price'][$iLessonTypeId][$i]);
                $lessonLevel->setTeacherId($iTeacherId);

                models_LessonLevelsMapper::save($lessonLevel);
            }
        }

        Lemar_Notifier::sendActivateAccountMessage($user, $activationCode);

        return $user;
    }

    /**
     * @author andrey
     * 
     * @param Zend_Form_Element_File $file
     * @param models_Users $user
     * @return string
     */
    public static function uploadTeacherPhoto(Zend_Form_Element_File $file, models_Users $user) {
        if (!$file->isUploaded())
            return null;

        $photoConfig = Zend_Registry::get('config')->photo;

        $sPhotoExtension = pathinfo(
                $file->getFilename(), PATHINFO_EXTENSION
        );
        $sNewPhotoDir = Lemar_File::getOrMakeDirPathForUploadedFile(
                        $user->getId(), $photoConfig->directory, true);
        /* $sNewPhotoFileName = $user->getFirstName() . '_' .
          $user->getLastName() . '.' . $sPhotoExtension; */
        $sNewPhotoFileName = $user->getId() . '.' . $sPhotoExtension;
        $sNewPhotoFilePath = $sNewPhotoDir . $sNewPhotoFileName;

        $file->addFilter(
                'Rename', array(
            'target' => $sNewPhotoFilePath,
            'overwrite' => true
                )
        );

        if (!$file->receive())
            return null;

        return $sNewPhotoFileName;
    }

    /**
     * 
     * @param models_Teachers $teacher
     * @return string
     */
    public static function getTeacherPhotoFilePath(models_Teachers $teacher) {
        return Lemar_File::getOrMakeDirPathForUploadedFile($teacher->getIdUser(), Zend_Registry::get('config')->photo->directory, false) .
                $teacher->getPhotoFile();
    }

    /**
     * 
     * @param int $idTeacher
     * @return string
     */
    public static function getTeacherPhotoPreviewFilePath($idTeacher) {
        $filePath = Zend_Registry::get('config')->photo->preview_directory .
                '/' . ceil($idTeacher / 1000) . '/' . $idTeacher . '.jpg';

        if (!file_exists($filePath))
            return null;

        return $filePath;
    }

    /**
     * 
     * @param models_Teachers $teacher
     * @return bool
     */
    public static function makePhotoPreview(models_Teachers $teacher) {
        if (!$teacher->getPhotoFile())
            return false;

        $imageName = App_User::getTeacherPhotoFilePath($teacher);

        if (!file_exists($imageName))
            return false;

        $imageExt = substr(strrchr($imageName, '.'), 1);

        if ($imageExt == 'jpg') {
            $imageExt = 'jpeg';
        }

        $imagecreatefrom = 'imagecreatefrom' . $imageExt;

        $im = $imagecreatefrom($imageName);
        $imWidth = imagesx($im);
        $imHeigth = imagesy($im);

        $photoConfig = Zend_Registry::get('config')->photo;

        $newImage = imagecreatetruecolor(100, 100);

        if ($imWidth >= $imHeigth) {
            imagecopyresampled($newImage, $im, 0, 0, ceil(($imWidth - $imHeigth) / 2), 0, 100, 100, $imHeigth, $imHeigth);
        } else {
            imagecopyresampled($newImage, $im, 0, 0, 0, 0, 100, 100, $imWidth, $imWidth);
        }

        $dirName = (string) ceil($teacher->getId() / 1000);

        if (!file_exists($photoConfig->preview_directory . '/' . $dirName)) {
            mkdir($photoConfig->preview_directory . '/' . $dirName, 0777);
        }

        @imagejpeg($newImage, $photoConfig->preview_directory . '/' . $dirName . '/' . $teacher->getId() . '.jpg');
        @chmod($photoConfig->preview_directory . '/' . $dirName . '/' . $teacher->getId() . '.jpg', 0777);

        return true;
    }
    
    public static function restorePassword($email) {
        $user = App_ServiceLocator::UserMapper()->findOneByEmail($email);
        if($user) {
            $newPassword = App_StringGenerator::generateRandom();
            $user->password = App_StringGenerator::encodePassword($newPassword);
            $user->save();
            $subject = 'Password restore';
            $body = 'Your new password is: '.$newPassword.'<br />This password was genereted automaticaly, so please change it in your Profile.';
            App_Mailer::send($body, $subject, $user->email, $user->fullname, 'lognllc@rsed.org', 'MyRemoteTeam');
            return true;
        }
        return false;
    }

}
