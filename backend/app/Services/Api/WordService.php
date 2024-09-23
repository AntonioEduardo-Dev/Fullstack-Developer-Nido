<?php

namespace App\Services\Api;

use App\Repositories\Api\WordRepository;
use Illuminate\Support\Facades\Http;

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

    public function importChunkWords($url)
    {
        // Baixar o arquivo
        $response = Http::get($url);
        
        if ($response->successful()) {
            $words = explode("\n", trim($response->body()));
            $this->wordRepository->insertChunkWords($words);
            return count($words);
        }

        throw new \Exception("Erro ao baixar o arquivo.");
    }

    public function listData($perPage, $cursor, $search) 
    {
        // Pega os resultados paginados com cursores
        $items = $this->wordRepository->cursorPaginate($search, $perPage, $cursor);
        
        // Conta todos os documentos na tabela, filtrando se necessário
        $totalDocs = $this->wordRepository->count($search); 
        $previous = $items->previousCursor()?->encode();
        $next = $items->nextCursor()?->encode();
        $hasNext = $items->hasMorePages();
        $hasPrev = !is_null($items->previousCursor());
        
        // Cria a estrutura de resposta
        return [
            'results' => collect($items->items())->map(function($item) {
                return $item->word; // Use a propriedade correta se $item for um objeto
            }),
            'totalDocs' => $totalDocs,
            'previous' => $previous,
            'next' => $next,
            'hasNext' => $hasNext,
            'hasPrev' => $hasPrev,
        ];
    }
}
