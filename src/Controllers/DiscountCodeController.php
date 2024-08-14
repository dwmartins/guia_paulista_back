<?php 

namespace App\Controllers;

use App\Class\DiscountCode;
use App\Class\User;
use App\Http\Request;
use App\Http\Response;
use App\Models\DiscountCodeDAO;
use App\Validators\DiscountCodeValidator;
use App\Validators\TextValidator;
use DateTime;
use Exception;

class DiscountCodeController {
    public function create(Request $request, Response $response) {
        try {
            $data = $request->body();

            if(!DiscountCodeValidator::createOrUpdate($data)) {
                return;
            }

            $discountCode = new DiscountCode($data);

            if(!empty(DiscountCodeDAO::fetchByCode($discountCode->getCode()))) {
                return $response->json([
                    'message' => DISCOUNT_ALREADY
                ], 400);
            }


            if(new DateTime($discountCode->getStartDate()) < new DateTime()) {
                return $response->json([
                    'message' => STAR_DATE_INVALID
                ], 400);
            }

            if(new DateTime($discountCode->getEndDate()) < new DateTime()) {
                return $response->json([
                    'message' => END_DATE_INVALID
                ], 400);
            }

            $discountCode->save();

            $response->json([
                'message' => CODE_CREATED
            ], 201);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => FATAL_ERROR
            ], 500);
        }
    }

    public function update(Request $request, Response $response) {
        try {
            $data = $request->body();

            if(!DiscountCodeValidator::createOrUpdate($data)) {
                return;
            }

            $discountCode = new DiscountCode($data);

            $codeExists = DiscountCodeDAO::fetchByCode($discountCode->getCode());
            if(!empty($codeExists)) {
                if($codeExists['id'] != $discountCode->getId()) {
                    return $response->json([
                        'message' => DISCOUNT_ALREADY
                    ], 400);
                }
            }

            if(new DateTime($discountCode->getStartDate()) < new DateTime()) {
                return $response->json([
                    'message' => STAR_DATE_INVALID
                ], 400);
            }

            if(new DateTime($discountCode->getEndDate()) < new DateTime()) {
                return $response->json([
                    'message' => END_DATE_INVALID
                ], 400);
            }

            $discountCode->save();

            $response->json([
                'message' => CODE_UPDATED
            ], 201);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => FATAL_ERROR
            ], 500);
        }
    }

    public function fetchAll(Request $request, Response $response) {
        try {
            $response->json(DiscountCodeDAO::fetchAll());

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => FATAL_ERROR
            ], 500);
        }
    }

    public function validate(Request $request, Response $response) {
        try {
            $data = $request->body();

            if(!isset($data['code']) || empty($data['code'])) {
                return $response->json([
                    'message' => sprintf(REQUIRED_FIELD, CODE_LABEL)
                ], 400);
            }

            if(!TextValidator::text($data['code'])) {
                return $response->json([
                    'message' => sprintf(FIELD_INVALID_CHARACTERS, CODE_LABEL)
                ], 400);
            }

            $discountCode = new DiscountCode(DiscountCodeDAO::fetchByCode($data['code']));
            
            if(empty($discountCode->getId()) || !$this->codeIsValid($discountCode, $data)) {
                return $response->json([
                    'message' => INVALID_CODE
                ], 400);
            }

            $discountCode->useDiscountCode();

            return $response->json([
                "message" => VALID_CODE,
                "discountCodeData" => $discountCode->toArray()
            ]);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => FATAL_ERROR
            ], 500);
        }
    }

    public function codeIsValid(DiscountCode $discountCode, array $data) {
        $currentDate = new DateTime();
        $startDate = new DateTime($discountCode->getStartDate());
        $endDate = new DateTime($discountCode->getEndDate());

        if($currentDate <= $startDate) {
            return false;
        }

        if($currentDate >= $endDate) {
            return false;
        }

        if($discountCode->getActive() == "N") {
            return false;
        }

        if($discountCode->getCurrentUses() >= $discountCode->getMaxUses()) {
            return false;
        }

        if(!empty($discountCode->getSponsor())) {
            $sponsor = new User();
            $sponsor->fetchById($discountCode->getSponsor());

            $userRequest = new User();
            $userRequest->fetchById($data['userId']);

            return $sponsor->getId() === $userRequest->getId();
        }

        if(!empty($discountCode->getModule())) {
            return $discountCode->getModule() === strtolower($data["module"]);
        }

        return true;
    }
}
