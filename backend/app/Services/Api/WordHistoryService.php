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

    public function makeUserFavorite(int $userId, string $word)
    {
        $word = $this->wordRepository->findByColumns(["word", $word]);

        return $this->wordHistoryRepository->updateOrCreate([
            'user_id' => $userId,
            'word_id' => $word['id']
        ]);
    }
}
