<?php

namespace App\Controllers;

use App\Class\SiteInfo;
use App\Class\User;
use App\Http\Request;
use App\Http\Response;
use App\Services\SendEmail;
use App\Validators\SendEmailValidators;
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

    public function contact(Request $request, Response $response) {
        try {
            $requestData = $request->body();

            if(!SendEmailValidators::contact($requestData)) {
                return;
            }

            $siteInfo = new SiteInfo();
            $siteInfo->fetch();

            include(__DIR__."/../EmailTemplates/contact.php");
            $template = ob_get_clean();

            $template = str_replace('{SITENAME}', $siteInfo->getWebSiteName(), $template);
            $template = str_replace('{CONTACT_NAME}', $requestData['name'], $template);
            $template = str_replace('{CONTACT_LASTNAME}', $requestData['lastName'], $template);
            $template = str_replace('{CONTACT_EMAIL}', $requestData['email'], $template);
            $template = str_replace('{CONTACT_COMPANY}', $requestData['company'], $template);
            $template = str_replace('{CONTACT_MESSAGE}', $requestData['message'], $template);

            $this->mailSend->setTo($siteInfo->getEmail());
            $this->mailSend->setSubject(WEBSITE_MESSAGE . " " . $siteInfo->getWebSiteName());
            $this->mailSend->setTemplate($template);
            $this->mailSend->send($siteInfo);

            return $response->json([
                'message' => MESSAGE_SENT
            ], 200);
        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => FATAL_ERROR
            ], 500);
        }
    }
}