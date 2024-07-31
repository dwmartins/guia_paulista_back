<?php

namespace App\Controllers;

use App\Class\User;
use App\Class\UserAccess;
use App\Class\UserPermissions;
use App\Http\JWTManager;
use App\Http\Request;
use App\Http\Response;
use App\Models\UserPermissionsDAO;
use App\Validators\UserValidators;
use Exception;

class AuthController {
    public function login(Request $request, Response $response) {
        try {
            $data = $request->body();

            if(!UserValidators::login($data)) {
                return;
            }

            $user = new User();

            if($user->fetchByEmail($data["email"])) {
                if($user->getActive() === "Y") {
                    if(password_verify($data["password"], $user->getPassword())) {
                        $userData = array(
                            "id"        => $user->getId(),
                            "name"      => $user->getName(),
                            "lastName"  => $user->getLastName(),
                            "email"     => $user->getEmail(),
                            "role"      => $user->getRole(),
                            "photo"     => $user->getPhoto(),
                            "token"     => JWTManager::generate($user, $data['rememberMe'] ?? false),
                            "createdAt" => $user->getCreatedAt(),
                            "updatedAt" => $user->getUpdatedAt()
                        );

                        if($user->getRole() === "mod") {
                            $userPermissions = new UserPermissions();
                            $userPermissions->getPermissions($user);

                            $userData["permissions"] = [
                                "users"        => $userPermissions->getUsers(),
                                "content"      => $userPermissions->getContent(),
                                "siteInfo"     => $userPermissions->getSiteInfo(),
                                "emailSending" => $userPermissions->getEmailSending()
                            ];
                        }

                        $userAccess = new UserAccess([
                            "user_id" => $user->getId(),
                            "ip"      => $request->getIp()
                        ]);

                        $userAccess->save();

                        return $response->json($userData);
                    }
                }
            }

            return $response->json([
                "message" => INVALID_CREDENTIALS
            ], 401); 
            
        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message" => FATAL_ERROR
            ], 500);
        }
    }

    public function auth(Request $request, Response $response) {
        try {
            $responseData = [];
            $headers = $request->authorization();

            if($headers) {
                $user = new User();

                if($user->fetchById($headers["userId"])) {
                    $tokenDecode = JWTManager::validate($headers["token"], $user);
                    
                    if(!empty($tokenDecode) && !isset($tokenDecode->expired)) {
                        $responseData["role"] = $user->getRole(); 

                        if($user->getRole() === "mod") {
                            $permissions = UserPermissionsDAO::getPermissions($user);
                            $responseData["permissions"] = $permissions;
                        }
                        
                        return $response->json($responseData);

                    } else if(isset($tokenDecode->expired)) {
                        return $response->json([
                            "message"      => SESSION_EXPIRED,
                            "expiredToken" => true,
                            "logout"       => true
                        ], 401);
                    }
                }
            }

            return $response->json([
                "message" => NOT_LOGGED_IN,
                "logout"  => true,
            ], 401);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message" => FATAL_ERROR
            ], 500);
        }
    }
}
