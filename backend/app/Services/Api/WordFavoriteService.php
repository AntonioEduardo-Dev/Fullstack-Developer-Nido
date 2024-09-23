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

    public function getFavorite(int $userId, $perPage, $cursor)
    {
        $query = $this->wordFavorityRepository->whereByColumns(["user_id" => $userId]);
        $items = $this->wordFavorityRepository->cursorPaginate(null, $perPage, $cursor, $query);

        // Conta todos os documentos na tabela, filtrando se necessário
        $totalDocs = $this->wordFavorityRepository->count(null); 
        $previous = $items->previousCursor()?->encode();
        $next = $items->nextCursor()?->encode();
        $hasNext = $items->hasMorePages();
        $hasPrev = !is_null($items->previousCursor());

        return response()->json([
            'results' => collect($items->items())->map(function($item) {
                return [
                    'word' => $item['word']['word'],
                    'added' => $item['word']['created_at']
                ]; // Use a propriedade correta se $item for um objeto
            }),
            'totalDocs' => $totalDocs,
            'previous' => $previous,
            'next' => $next,
            'hasNext' => $hasNext,
            'hasPrev' => $hasPrev,
        ], 200);
    }
}
