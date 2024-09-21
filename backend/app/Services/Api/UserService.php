<?php

namespace App\Services\Api;

use App\Exceptions\ClientException;

class UserService
{
    public function __construct()
    {}
    
    public function getAuthenticatedUser()
    {
        $user = auth()->guard('api_jwt')->user();
    
        if (!$user) {
            throw new ClientException('User not logged');
        }

        return $user ?? null;
    }
}
