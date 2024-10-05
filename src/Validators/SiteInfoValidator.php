<?php

namespace App\Validators;

use App\Class\SiteInfo;
use App\Http\Response;

class SiteInfoValidator {
    public static function create(array $data) {
        $fields = [
            "webSiteName" => [
                "label" => SITE_NAME_LABEL,
                "required" => false
            ],
            "email" => [
                "label" => EMAIL_LABEL,
                "required" => false
            ],
            "phone" => [
                "label" => PHONE_LABEL,
                "required" => false
            ],
            "city" => [
                "label" => CITY_LABEL,
                "required" => false
            ],
            "state" => [
                "label" => STATE_LABEL,
                "required" => false
            ],
            "address" => [
                "label" => ADDRESS_LABEL,
                "required" => false
            ],
            "instagram" => [
                "label" => INSTAGRAM_LABEL,
                "required" => false
            ],
            "facebook" => [
                "label" => FACEBOOK_LABEL,
                "required" => false
            ],
            "twitter" => [
                "label" => TWITTER_LABEL,
                "required" => false
            ],
            "description" => [
                "label" => DESCRIPTION_LABEL,
                "required" => false
            ],
            "keywords" => [
                "label" => KEYWORDS_LABEL,
                "required" => false
            ]
        ];

        $response = [
            "isValid" => true,
            "message" => ""
        ];

        foreach ($data as $key => $value) {
            if(empty($value) && !$fields[$key]['required']) {
                continue;
            }

            if(empty($value) && $fields[$key]['required']) {
                $response['isValid'] = false;
                $response['message'] = sprintf(REQUIRED_FIELD, $fields[$key]['label']);
                return $response;
            }

            if(!TextValidator::text($value)) {
                $response['isValid'] = false;
                $response['message'] = sprintf(FIELD_INVALID_CHARACTERS, $fields[$key]['label']);
                return $response;
            }
        }

        return $response;
    }
}