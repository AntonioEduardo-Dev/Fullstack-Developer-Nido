<?php

namespace App\Repositories\Api;

use App\Interfaces\Api\WordRepositoryInterface;
use App\Models\Word;
use App\Repositories\{
    BaseRepository
};

/**
 * Repositorio para manipulação de dados de palavras da api.
 */
class WordRepository extends BaseRepository implements WordRepositoryInterface
{
    public function __construct(protected Word $word) {
        parent::__construct($word);
    }

    public function saveInBatch(array $words): void {
        $this->word::firstOrCreate($words); // Inserir cada lote
    }
}
