<?php

namespace App\Repositories\Api;

use App\Interfaces\Api\WordRepositoryInterface;
use App\Models\Word;
use App\Repositories\{
    BaseRepository
};
use Illuminate\Support\Facades\{
    DB
};

/**
 * Repositorio para manipulação de dados de palavras da api.
 */
class WordRepository extends BaseRepository implements WordRepositoryInterface
{
    protected $table = 'words';
    
    public function __construct(protected Word $word) {
        parent::__construct($word);
    }

    public function saveInBatch(array $words): void {
        $this->word::firstOrCreate($words); // Inserir cada lote
    }

    public function insertChunkWords(array $words)
    {
        // Normaliza as palavras
        $normalizedWords = array_map(function ($word) {
            return strtolower(trim($word));
        }, $words);
    
        $uniqueWords = array_unique($normalizedWords);
        $chunks = array_chunk($uniqueWords , 500); // Dividir em lotes de 100
    
        foreach ($chunks as $chunk) {
            DB::table($this->table)->insertOrIgnore(array_map(function ($word) {
                return ['word' => $word];
            }, $chunk));
        }
    }
}
