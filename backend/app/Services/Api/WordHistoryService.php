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

    public function getHistory(int $userId, $perPage, $cursor)
    {
        $query = $this->wordHistoryRepository->whereByColumns(["user_id" => $userId]);
        $items = $this->wordHistoryRepository->cursorPaginate(null, $perPage, $cursor, $query);

        // Conta todos os documentos na tabela, filtrando se necessário
        $totalDocs = $this->wordHistoryRepository->count(null); 
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
