<?php

namespace App\Validators;

use App\Http\Response;
use App\Validators\Validator;
use Exception;

class SendEmailValidators {
    public static function contact(array $data) {
        try {
            $fields = [
                NAME_LABEL       => $data['name'] ?? '',
                LAST_NAME_LABEL  => $data['lastName'] ?? '',
                EMAIL_LABEL      => $data['email'] ?? '',
                MESSAGE_LABEL    => $data['message'] ?? ''
            ];
    
            Validator::validate($fields);
    
            $fields[COMPANY_LABEL] = $data['company'];
    
            foreach ($fields as $key => $value) {
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
    
                if($key === NAME_LABEL || $key === LAST_NAME_LABEL) {
                    if(!TextValidator::name($value)) {
                        Response::json([
                            'message'   => sprintf(FIELD_INVALID_CHARACTERS, $key)
                        ], 400);
        
                        return false;
                    }
        
                    continue;
                }
    
                if(!TextValidator::text($value)) {
                    Response::json([
                        'error'     => true,
                        'message'   => sprintf(FIELD_INVALID_CHARACTERS, $key)
                    ], 400);
        
                    return false;
                }
            }
    
            return true;
            
        } catch (Exception $e) {
            Response::json([
                'message'   => $e->getMessage()
            ], 400);

            return false;
        }
    }
}