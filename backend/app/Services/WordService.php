<?php

namespace App\Services;

use App\Repositories\Api\WordRepository;

class WordService
{
    public function __construct(protected WordRepository $wordRepository)
    {}

    public function importWords(array $listWords)
    {
        foreach ($listWords as $word) {
            $word = trim($word);
            if (!!$word && !empty($word)) {
                 $this->wordRepository->saveInBatch(['word' => $word]);
            }
        }
    }
}
