<?php 

namespace App\Validators;

use App\Http\Response;

class CategoryValidator {
    public static function create(array $data){
        $fields = [
            NAME_LABEL => $data['name'],
            SLUG_URL_LABEL => $data['slugUrl'],
            STATUS_LABEL => $data['status']
        ];

        if(empty($data['name'])) {
            Response::json([
                'message' => sprintf(REQUIRED_FIELD, NAME_LABEL)
            ]);

            return false;
        }

        foreach ($fields as $key => $value) {
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