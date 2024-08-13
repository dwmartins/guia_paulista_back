<?php 

namespace App\Validators;

use App\Http\Response;
use App\Validators\Validator;
use Exception;

class DiscountCodeValidator {
    public static function createOrUpdate(array $data) {
        try {
            $fields = [
                CODE_LABEL      => $data["code"] ?? "",
                DISCOUNT_LABEL  => $data["discount"] ?? 0,
            ];

            Validator::validate($fields);

            $fields[MODULE_LABEL] = $data["module"] ?? "";

            foreach ($fields as $key => $value) {
                if(empty($value)) {
                    continue;
                }

                if($key === DISCOUNT_LABEL) {
                    if(!is_numeric($value)) {
                        Response::json([
                            'message'   => INVALID_DISCOUNT
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

        } catch (Exception $e) {
            Response::json([
                'message'   => $e->getMessage()
            ], 400);

            return false;
        }
    }
}