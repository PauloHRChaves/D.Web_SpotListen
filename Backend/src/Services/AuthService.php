<?php
namespace src\Services;

use src\Infrastructure\UserRepository;

use src\Exceptions\ApiException;

class AuthService {
    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    public function registerUser(string $registerEmail, string $username, string $registerPassword): bool {
        if ($this->userRepository->existsByEmailOrUsername($registerEmail, $username)) {
            throw new ApiException("Email ou username já em uso.", 409); 
        }
        
        $hashedPassword = password_hash($registerPassword, PASSWORD_DEFAULT);
        
        return $this->userRepository->save($registerEmail, $username, $hashedPassword);
    }

    public function loginUser(string $email, string $password): array {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new ApiException("E-mail ou senha incorretos.", 401); 
        }
        
        if (!password_verify($password, $user['USER_PASSWORD'])) {
            throw new ApiException("E-mail ou senha incorretos.", 401);
        }
        
        return [
            'id' => $user['ID'], 
            'username' => $user['USERNAME'],
        ];
    }

    public function getUserDataById(int $userId): array {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new ApiException("Usuário não encontrado.", 404);
        }

        return [
            'username' => $user['USERNAME'],
        ];
    }
}