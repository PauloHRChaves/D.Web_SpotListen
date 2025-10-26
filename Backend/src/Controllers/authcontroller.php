<?php
namespace src\Controllers;

use src\Models\User;
use src\Services\SpotifyService;

class AuthController{
    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        $email = trim($_POST['register-Email']);
        $username = trim($_POST['username']);
        $password = trim($_POST['register-password']);

        $createResult = $this->userModel->createuser($email, $username, $password);

        if ($createResult === true) {
            http_response_code(201);
            echo json_encode(["message" => "Usuário cadastrado com sucesso!"], JSON_UNESCAPED_UNICODE);
        } 
        elseif ($createResult === "email_or_username_exists") {
            http_response_code(409);
            echo json_encode(['message' => 'Este E-mail ou Nome já está em uso.'], JSON_UNESCAPED_UNICODE);
        } 
        else {
            http_response_code(500);
            echo json_encode(['message' => 'Erro interno do servidor. Por favor, tente novamente mais tarde.'], JSON_UNESCAPED_UNICODE);
        }
    }

    public function spotifyLoginRedirect() {
        $sptService= new SpotifyService();
        $this->sptService->spotifyLogin(); 
    }
}