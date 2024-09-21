<?php

namespace App\Services\Api;

use App\Exceptions\ClientException;
use App\Repositories\Api\WordRepository;

class UserService
{
    public function __construct(protected WordRepository $wordRepository)
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
