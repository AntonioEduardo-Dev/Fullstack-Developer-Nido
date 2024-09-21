<?php

namespace App\Repositories\Api;

use App\Interfaces\WordInterface;
use App\Models\Word;
use App\Repositories\{
    BaseRepository
};

/**
 * Repositorio para manipulação de dados do usuario da api.
 */
class WordRepository extends BaseRepository implements WordInterface
{
    public function __construct(protected Word $word) {
        parent::__construct($word);
    }
}
