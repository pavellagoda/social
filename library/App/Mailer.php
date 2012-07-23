<?php

/**
 * Mailer class
 */

class App_Mailer {

    /**
     * Send mail
     *
     * @param string $sBody - message text body
     * @param string $sToAddress - address to
     * @param string $sToName - name to
     * @param string $sFromAddress - address from
     * @param string $sFromName - name from
     * @param string $sSubject - subject
     *
     * @return bool
     */
    public static function send(
        $sBody, $sSubject='mail subject', $sToAddress = 'alert-studentportaloverride@rsed.org', $sToName = 'StudentPortal', $sFromAddress='lognllc@rsed.org', $sFromName='StudentPortal'
    ) 
    {
        $config = array(
            'auth' => 'login',
            'username' => 'lognllc@rsed.org',
            'password' => 'pass4yevgen',
            'port' => 587,
            'ssl' => 'tls'
        );

        $tr = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
        Zend_Mail::setDefaultTransport($tr);

        $oMail = new Zend_Mail('UTF-8');

        $oMail->setBodyHtml($sBody);
        $oMail->setFrom($sFromAddress, $sFromName);
        $oMail->setReplyTo($sFromAddress, $sFromName);
        $oMail->addTo($sToAddress, $sToName);
        $oMail->setSubject($sSubject);
        if ($oMail->send()) {
            return true;
        } else {
//                      Logger::log(
//                              'Mailer::send: error  Zend_Mail->send() ',
//                          $iPriority = Zend_Log::ERR
//            );
        }
        return false;
    }

}
