<?php

namespace App\Services\Api;

use App\Repositories\Api\WordHistoryRepository;
use App\Repositories\Api\WordRepository;

class WordHistoryService
{
    public function __construct(
        protected WordRepository $wordRepository,
        protected WordHistoryRepository $wordHistoryRepository
    )
    {}

    public function makeUserHistory(int $userId, string $word)
    {
        $wordData = $this->wordRepository->findByColumns(["word" => $word]);
        return $this->wordHistoryRepository->updateOrCreate([
            'user_id' => $userId,
            'word_id' => $wordData['id']
        ]);
    }

    public function unMakeUserHistory(int $userId, string $word)
    {
        $wordData = $this->wordRepository->findByColumns(["word" => $word]);
        return $this->wordHistoryRepository->deleteByColumns([
            'user_id' => $userId,
            'word_id' => $wordData['id']
        ]);
    }
}
