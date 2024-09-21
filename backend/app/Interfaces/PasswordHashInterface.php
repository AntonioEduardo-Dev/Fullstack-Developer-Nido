<?php

namespace App\Interfaces;

interface PasswordHashInterface 
{
    public function hash(string $password): string;

    public function checkHash(string $password, string $currentPassword): bool;
}
