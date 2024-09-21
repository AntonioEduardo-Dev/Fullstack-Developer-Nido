<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Services\Api\AuthService;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
    )
    {}

    public function signUp(AuthRegisterRequest $request)
    {
        return $this->authService->signUp($request->validated());
    }

    public function signIn(AuthLoginRequest $request)
    {
        return $this->authService->signIn($request->validated());
    }
}
