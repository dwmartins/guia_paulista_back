<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Http\JWTManager;
use App\Validators\UserValidators;
use App\Class\User;
use App\Class\UserPermissions;
use App\Models\UserDAO;
use App\Utils\UploadFile;
use App\Validators\FileValidators;
use DateTime;
use Exception;

class UserController {
    private string $userImagesFolder = "users";
    private array $permissions = [
        "users" => [
            "allowed" => false,
            "label" => "Users"
        ],
        "content" => [
            "allowed" => false,
            "label" => "Website content"
        ],
        "siteInfo" => [
            "allowed" => false,
            "label" => "Website information"
        ],
        "settings" => [
            "allowed" => false,
            "label" => "Site Settings"
        ],
        "emailSettings" => [
            "allowed" => false,
            "label" => "Email Settings"
        ]
    ];

    public function create(Request $request, Response $response) {
        try {
            $body = $request->body();

            if(!UserValidators::create($body)) {
                return;
            }

            if(!empty(UserDAO::fetchByEmail($body['email']))) {
                return $response->json([
                    'message'   => EMAIL_IN_USE
                ], 409);
            }

            $user = new User($body);
            $user->setToken(JWTManager::newCrypto());
            $user->setPassword($body['password']);
            $user->save();

            $sendEmail = new SendEmailController($user->getEmail());
            $sendEmail->welcome($user);

            $response->json([
                'message' => USER_CREATED
            ], 201);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => FATAL_ERROR
            ], 500);
        }
    }

    public function setPermissions(User $user, array $permissions) {
        $userPermissions = [];

        if(!empty($permissions)) {
            foreach ($permissions as $key => $value) {
                $this->permissions[$key]['allowed'] = $permissions[$key]['allowed'];
                $userPermissions[$key] = $permissions[$key];
            }

            $userPermissions['userId'] = $user->getId();
        }

        $permissions = new UserPermissions($this->permissions);

        if(!empty($permissions->getPermissions($user))) {
            $permissions->update($this->permissions);
        }

        $permissions->save();
    }

    public function setPhoto(Request $request, Response $response) {
        try {
            $files = $request->files();

            if(!isset($files['photo']) || empty($files['photo'])) {
                return $response->json([
                    'message' => NOT_IMAGES_SENT
                ], 400);
            }

            $user = $request->getAttribute('userRequest');

            $fileData = FileValidators::validImage($files['photo']);

            if(isset($fileData['invalid'])) {
                return $response->json([
                    'message' => $fileData['invalid']
                ], 400);
            }

            if(!empty($user->getPhoto())) {
                UploadFile::removeFile($user->getPhoto(), $this->userImagesFolder);
            }
            
            $fileName = $user->getId() . "_user." . $fileData['mimeType'];
            UploadFile::uploadFile($files['photo'], $this->userImagesFolder, $fileName);

            $user->setPhoto($fileName);
            $user->save();
            
            $userData = $user->toArray();
            unset($userData['token']);
            unset($userData['password']);

            return $response->json([
                "message" => UPDATED_IMAGE_USER,
                "userData" => $userData
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
            $requestBody = $request->body();
            $user = $request->getAttribute('userRequest');

            if(!UserValidators::update($requestBody)) {
                return;
            }

            $emailExists = UserDAO::fetchByEmail($requestBody['email']);

            if($emailExists && $emailExists['id'] != $user->getId()) {
                return $response->json([
                    'message'   => EMAIL_IN_USE
                ], 409);
            }

            $user->update($requestBody);

            if(!empty($requestBody['newPassword'])) {
                $user->setPassword($requestBody['newPassword']);
            }

            $user->save();

            $userData = $user->toArray();
            unset($userData['password']);
            unset($userData['token']);

            return $response->json([
                'message'   => USER_UPDATE,
                'userData' =>  $userData
            ], 201);


        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => FATAL_ERROR
            ], 500);
        }
    }

    public function updateAddress(Request $request, Response $response) {
        try {
            $requestBody = $request->body();
            $user = $request->getAttribute('userRequest');

            if(!UserValidators::updateAddress($requestBody)) {
                return;
            }

            $user->update($requestBody);
            $user->save();

            $userData = $user->toArray();
            unset($userData['password']);
            unset($userData['token']);

            return $response->json([
                'message'   => ADDRESS_UPDATE,
                'userData' =>  $userData
            ], 201);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => FATAL_ERROR
            ], 500);
        }
    }

    public function updateSettings(Request $request, Response $response) {
        try {
            $requestBody = $request->body();
            $user = $request->getAttribute('userRequest');

            $user->update($requestBody);
            $user->save();

            $userData = $user->toArray();
            unset($userData['password']);
            unset($userData['token']);

            return $response->json([
                'message'   => CONFIG_USER_UPDATE,
                'userData' =>  $userData
            ], 201);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => FATAL_ERROR
            ], 500);
        }
    }

    public function delete(Request $request, Response $response, $params) {
        try {
            $user = new User();
            $user->fetchById($params[0]);

            if(!empty($user->getPhoto())) {
                UploadFile::removeFile($user->getPhoto(), $this->userImagesFolder);
            }

            $user->delete();

            return $response->json([
                'message'   => DELETE_ACCOUNT,
            ], 201);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => FATAL_ERROR
            ], 500);
        }
    }
}