<?php

namespace App\Interfaces\Api;

interface WordRepositoryInterface 
{
    public function saveInBatch(array $words): void;
}
