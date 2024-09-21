<?php

namespace App\Services\Api;

use App\Repositories\Api\GuzzleHttpRepository;

class DicionaryApiService
{
    public function __construct(protected GuzzleHttpRepository $guzzleHttpRepository)
    {}

    public function getAllItems($word)
    {
        return $this->guzzleHttpRepository->getAllItems('GET', "https://api.dictionaryapi.dev/api/v2/entries/en/{$word}");
    }
}
