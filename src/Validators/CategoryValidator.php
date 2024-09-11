<?php 

namespace App\Validators;

use App\Http\Response;

class CategoryValidator {
    public static function create(array $data){
        $fields = [
            NAME_LABEL => $data['name'] ?? "",
            SLUG_URL_LABEL => $data['slugUrl'] ?? "",
        ];

        if(empty($data['name'])) {
            Response::json([
                'message' => sprintf(REQUIRED_FIELD, NAME_LABEL)
            ], 400);

            return false;
        }

        foreach ($fields as $key => $value) {
            if(empty($value)) {
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