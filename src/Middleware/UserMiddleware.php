<?php

namespace App\Middleware;

use App\Class\User;
use App\Class\UserPermissions;
use App\Http\JWTManager;
use App\Http\Request;
use App\Http\Response;
use Exception;

class UserMiddleware {
    private static array $allowedRoles = ["support", "admin", "mod", "test"];
    private static array $noPermissionNeeded = ["support", "admin"];

    public static function isAuth(Request $request, Response $response) {
        try {
            $headers = $request->authorization();

            $token  = $headers["token"] ?? "";
            $userId = $headers["userId"] ?? "";

            if(!$token) {
                return $response::json([
                    "message" => NOT_LOGGED_IN,
                    "logout"  => true
                ], 401);
            }

            $user = new User();
            $user->fetchById($userId);

            if(!empty($user->getId()) && $user->getActive() === "Y") {
                $decoded = JWTManager::validate($token, $user);

                if(!$decoded) {
                    return $response::json([
                        "message" => NOT_LOGGED_IN,
                        "logout"  => true
                    ], 401);
                }

                if(isset($decoded->expired)) {
                    return $response::json([
                        "message" => SESSION_EXPIRED,
                        "logout"  => true
                    ], 401);
                }

                $request->setAttribute("userRequest", $user);
                return true;

            } else {
                return $response::json([
                    "message"  => NOT_LOGGED_IN,
                    "redirect" => true
                ], 401);
            }

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message"  => FATAL_ERROR,
                "redirect" => true
            ], 500);
        }
    }

    public static function isAdmin(Request $request, Response $response) {
        try {
            $headers = $request->authorization();

            $token  = $headers["token"] ?? "";
            $userId = $headers["userId"] ?? "";

            if(!$token) {
                return $response::json([
                    "message" => NOT_LOGGED_IN,
                    "logout"  => true
                ], 401);
            }

            $user = new User();
            $user->fetchById($userId);

            if(!empty($user) && $user->getActive() === "Y") {
                $decoded = JWTManager::validate($token, $user);

                if(!$decoded) {
                    return $response::json([
                        "message" => NOT_LOGGED_IN,
                        "logout"  => true
                    ], 401);
                }

                if(isset($decoded->expired)) {
                    return $response::json([
                        "message" => SESSION_EXPIRED,
                        "logout"  => true
                    ], 401);
                }

                if(!in_array($user->getRole(), self::$allowedRoles)) {
                    return $response::json([
                        "message"  => NO_PERMISSION_TO_ACCESS,
                        "redirect" => true
                    ], 403);
                }

                $request->setAttribute("userRequest", $user);
                return true;

            } else {
                return $response::json([
                    "message"  => NOT_LOGGED_IN,
                    "redirect" => true
                ], 401);
            }

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message"  => FATAL_ERROR,
                "redirect" => true
            ], 500);
        }
    }

    public function contents(Request $request, Response $response) {
        try {
            $user = $request->getAttribute("userRequest");

            if(in_array($user->getRole(), self::$noPermissionNeeded)) {
                return true;
            }

            if($user->getRole() === "test") {
                return $response::json([
                    "message" => TEXT_MESSAGE_ACCOUNT
                ], 403);
            }

            $permission = new UserPermissions();
            $permission->getPermissions($user);

            if(!empty($permission->getId())) {
                $toContent = $permission->getContent();

                if($toContent["allowed"]) {
                    return true;
                }

                return $response::json([
                    "message" => NOT_ALLOWED
                ], 403);
            }

            return $response::json([
                "message" => NOT_ALLOWED
            ], 403);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message"  => FATAL_ERROR,
                "redirect" => true
            ], 500);
        }
    }

    public function permissionsToUsers(Request $request, Response $response) {
        try {
            $user = $request->getAttribute("userRequest");

            if(in_array($user->getRole(), self::$noPermissionNeeded)) {
                return true;
            }

            if($user->getRole() === "test") {
                return $response::json([
                    "message" => TEXT_MESSAGE_ACCOUNT
                ], 403);
            }

            $permission = new UserPermissions();
            $permission->getPermissions($user);

            if(!empty($permission->getId())) {
                $toUser = $permission->getUsers();

                if($toUser["allowed"]) {
                    return true;
                }

                return $response::json([
                    "message" => NOT_ALLOWED
                ], 403);
                
            }

            return $response::json([
                "message" => NOT_ALLOWED
            ], 403);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message" => FATAL_ERROR
            ], 500);
        }
    }

    public function settings(Request $request, Response $response) {
        try {
            $user = $request->getAttribute("userRequest");

            if(in_array($user->getRole(), self::$noPermissionNeeded)) {
                return true;
            }

            if($user->getRole() === "test") {
                return $response::json([
                    "message" => TEXT_MESSAGE_ACCOUNT
                ], 403);
            }

            $userPermission = new UserPermissions();
            $userPermission->getPermissions($user);

            if(!empty($userPermission->getId())) {
                $settings = $userPermission->getEmailSending();

                if($settings["allowed"]) {
                    return true;
                }
            }

            return $response::json([
                "message" => NOT_ALLOWED
            ], 403);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message" => FATAL_ERROR
            ], 500);
        }
    }

    public function emailSendingSettings(Request $request, Response $response) {
        try {
            $user = $request->getAttribute("userRequest");

            if(in_array($user->getRole(), self::$noPermissionNeeded)) {
                return true;
            }

            if($user->getRole() === "test") {
                return $response::json([
                    "message" => TEXT_MESSAGE_ACCOUNT
                ], 403);
            }

            $userPermission = new UserPermissions();
            $userPermission->getPermissions($user);

            if(!empty($userPermission->getId())) {
                $emailSendConfig = $userPermission->getEmailSending();

                if($emailSendConfig["allowed"]) {
                    return true;
                }
            }

            return $response::json([
                "message" => NOT_ALLOWED
            ], 403);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message" => FATAL_ERROR
            ], 500);
        }
    }

    public function siteInfo(Request $request, Response $response) {
        try {
            $user = $request->getAttribute("userRequest");

            if(in_array($user->getRole(), self::$noPermissionNeeded)) {
                return true;
            }

            if($user->getRole() === "test") {
                return $response::json([
                    "message" => TEXT_MESSAGE_ACCOUNT
                ], 403);
            }

            $userPermission = new UserPermissions();
            $userPermission->getPermissions($user);

            if(!empty($userPermission->getId())) {
                $siteInfoConfig = $userPermission->getSiteInfo();

                if($siteInfoConfig["allowed"]) {
                    return true;
                }
            }

            return $response::json([
                "message" => NOT_ALLOWED
            ], 403);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "error"   => true,
                "message" => FATAL_ERROR
            ], 500);
        }
    }
}