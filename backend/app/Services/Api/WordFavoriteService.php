<?php

namespace App\Services\Api;

use App\Repositories\Api\WordFavorityRepository;
use App\Repositories\Api\WordRepository;

class WordFavoriteService
{
    public function __construct(
        protected WordRepository $wordRepository,
        protected WordFavorityRepository $wordFavorityRepository
    )
    {}

    public function makeUserFavorite(int $userId, string $word)
    {
        $wordData = $this->wordRepository->findByColumns(["word" => $word]);
        return $this->wordFavorityRepository->updateOrCreate([
            'user_id' => $userId,
            'word_id' => $wordData['id']
        ]);
    }

    public function unMakeUserFavorite(int $userId, string $word)
    {
        $wordData = $this->wordRepository->findByColumns(["word" => $word]);
        return $this->wordFavorityRepository->deleteByColumns([
            'user_id' => $userId,
            'word_id' => $wordData['id']
        ]);
    }
}
