<?php

namespace App\Controllers;

use App\Class\SiteInfo;
use App\Class\User;
use App\Services\SendEmail;
use Exception;

class SendEmailController {
    private $mailSend;

    public function __construct() {
        $this->mailSend = new SendEmail();
    }

    public function welcome(User $user) {
        try {
            $siteInfo = new SiteInfo();
            $siteInfo->fetch();

            include(__DIR__."/../EmailTemplates/welcome.php");
            $template = ob_get_clean();
            
            $template = str_replace('{SITENAME}', $siteInfo->getWebSiteName(), $template);
            $template = str_replace('{USERNAME}', $user->getName(), $template);

            $this->mailSend->setTo($user->getEmail());
            $this->mailSend->setSubject(WELCOME_TITLE . " " . $siteInfo->getWebSiteName());
            $this->mailSend->setTemplate($template);
            $this->mailSend->send($siteInfo);

        } catch (Exception $e) {
            throw $e;
        }
    }
}