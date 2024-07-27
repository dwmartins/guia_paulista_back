<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Http\JWTManager;
use App\Validators\UserValidators;
use App\Class\User;
use App\Class\UserPermissions;
use App\Models\UserDAO;
use Exception;

class UserController {
    private string $userImagesFolder = "userImages";
    private array $permissions = [
        "users" => [
            "allowed" => false,
            "label" => "Usuários"
        ],
        "content" => [
            "allowed" => false,
            "label" => "Conteúdos do site"
        ],
        "siteInfo" => [
            "allowed" => false,
            "label" => "Informações do site"
        ],
        "emailSettings" => [
            "allowed" => false,
            "label" => "Configurações de e-mail"
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
                    'message'   => "Este e-mail já está em uso."
                ], 409);
            }

            $user = new User($body);
            $user->setToken(JWTManager::newCrypto());
            $user->setPassword($body['password']);
            $user->save();

            $response->json([
                'message' => "Sua conta foi criada com sucesso."
            ], 201);

        } catch (Exception $e) {
            logError($e->getMessage());
            return $response->json([
                'message' => "Falha ao criar o usuário."
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
}