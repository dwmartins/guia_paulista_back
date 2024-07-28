<?php 

namespace App\Validators;

class Validator {
    public static function validate(array $fields) {
        foreach ($fields as $field => $value) {
            if(empty(trim($value))) {
                throw new \Exception(sprintf(REQUIRED_FIELD, $field));
            }
        }

        return $fields;
    }
}