<?php

namespace App\Services\Api;

use App\Exceptions\ClientException;
use App\Repositories\Api\UserRepository;
use App\Services\PasswordHashAdapter;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected PasswordHashAdapter $passwordHash
    )
    {}
    
    public function signUp($data)
    {
        $user = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $this->passwordHash->hash($data['password']) ?? "123456789"
        ]);

        $token = JWTAuth::fromUser($user);
        $this->userRepository->update($user['id'], ["token" => $token]);

        return response()->json([
            'id' => $user['id'],
            'name' => $user['name'],
            'token' => "Bearer {$token}",
        ], 200);
    }

    public function signIn($credentials)
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            throw new ClientException('Unauthorized');
        }

        $user = JWTAuth::user();
        $this->userRepository->update($user['id'], ["token" => $token]);
        
        return response()->json([
            'id' => $user['id'],
            'name' => $user['name'],
            'token' => "Bearer {$token}",
        ], 200);
    }
}
