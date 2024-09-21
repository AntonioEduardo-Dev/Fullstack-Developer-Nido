<?php

namespace App\Services\Api;

use App\Repositories\cURLRepository;

class DicionaryApiService
{
    public function __construct(protected cURLRepository $cURLRepository)
    {}

    public function getAllItems($word)
    {
        return $this->cURLRepository->getAllItems('GET', "https://api.dictionaryapi.dev/api/v2/entries/en/{$word}");
    }
}
