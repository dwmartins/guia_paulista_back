<?php

namespace App\Validators;

use App\Class\SiteInfo;
use App\Http\Response;

class SiteInfoValidator {
    public static function create(SiteInfo $siteInfo) {
        $fields = [
            SITE_NAME_LABEL    => $siteInfo->getWebSiteName(),
            EMAIL_LABEL        => $siteInfo->getEmail(),
            PHONE_LABEL        => $siteInfo->getPhone(),
            CITY_LABEL         => $siteInfo->getCity(),
            STATE_LABEL        => $siteInfo->getState(),
            ADDRESS_LABEL      => $siteInfo->getAddress(),
            INSTAGRAM_LABEL    => $siteInfo->getInstagram(),
            FACEBOOK_LABEL     => $siteInfo->getFacebook(),
            DESCRIPTION_LABEL  => $siteInfo->getDescription(),
            KEYWORDS_LABEL     => $siteInfo->getKeywords(),
        ];

        foreach($fields as $key => $value) {
            if(empty($value)) {
                continue;
            }

            if($key === EMAIL_LABEL) {
                if(!TextValidator::email($value)) {
                    Response::json([
                        'message'   => INVALID_EMAIL
                    ], 400);

                    return false;
                }

                continue;
            }

            if($key === INSTAGRAM_LABEL || $key === FACEBOOK_LABEL) {
                if(!TextValidator::url($value)) {
                    Response::json([
                        'message'   => sprintf(INVALID_FIELD_ERROR, $key)
                    ], 400);

                    return false;
                }

                continue;
            }

            if(!TextValidator::text($value)) {
                Response::json([
                    'message'   => sprintf(FIELD_INVALID_CHARACTERS, $key)
                ], 400);

                return false;
            }
        }

        return true;
    }
}