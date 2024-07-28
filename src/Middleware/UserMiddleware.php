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
                    "message" => "Realize o login novamente para continuar.",
                    "logout"  => true
                ], 401);
            }

            $user = new User();
            $user->fetchById($userId);

            if(!empty($user) && $user->getActive() === "Y") {
                $decoded = JWTManager::validate($token, $user);

                if(!$decoded) {
                    return $response::json([
                        "message" => "Realize o login novamente para continuar.",
                        "logout"  => true
                    ], 401);
                }

                if(isset($decoded->expired)) {
                    return $response::json([
                        "message" => "Sua sessão expirou, realize o login novamente.",
                        "logout"  => true
                    ], 401);
                }

                if(!in_array($user->getRole(), self::$allowedRoles)) {
                    return $response::json([
                        "message"  => "Você não tem permissão para acessar essa área.",
                        "redirect" => true
                    ], 403);
                }

                $request->setAttribute("userRequest", $user);
                return true;

            } else {
                return $response::json([
                    "message"  => "Realize o login novamente para continuar.",
                    "redirect" => true
                ], 401);
            }

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message"  => "Oops, Ocorreu um erro inesperado.",
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
                    "message" => "Esta conta é apenas para teste, não tem permissões para editar, criar ou excluir algo."
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
                    "message" => "Você não tem permissão para executar essa ação."
                ], 403);
                
            }

            return $response::json([
                "message" => "Você não tem permissão para executar essa ação."
            ], 403);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message" => "Oops, Ocorreu um erro inesperado."
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
                    "message" => "Esta conta é apenas para teste, não tem permissões para editar, criar ou excluir algo."
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
                "message" => "Você não tem permissão para executar essa ação."
            ], 403);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message" => "Oops, Ocorreu um erro inesperado."
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
                    "message" => "Esta conta é apenas para teste, não tem permissões para editar, criar ou excluir algo."
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
                "message" => "Você não tem permissão para executar essa ação."
            ], 403);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "message" => "Oops, Ocorreu um erro inesperado."
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
                    "message" => "Esta conta é apenas para teste, não tem permissões para editar, criar ou excluir algo."
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
                "message" => "Você não tem permissão para executar essa ação."
            ], 403);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                "error"   => true,
                "message" => "Oops, Ocorreu um erro inesperado."
            ], 500);
        }
    }
}