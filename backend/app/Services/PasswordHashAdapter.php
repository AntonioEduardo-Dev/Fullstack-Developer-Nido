<?php

namespace App\Services;

use App\Interfaces\PasswordHashInterface;
use Illuminate\Support\Facades\Hash;

class PasswordHashAdapter implements PasswordHashInterface
{
    public function hash(string $password): string
    {
        return Hash::make($password);
    }

    public function checkHash(string $password, string $currentPassword): bool
    {
        return Hash::check($password, $currentPassword);
    }
}
