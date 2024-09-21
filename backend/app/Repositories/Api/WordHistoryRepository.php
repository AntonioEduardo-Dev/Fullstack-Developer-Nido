<?php

namespace App\Repositories\Api;

use App\Interfaces\WordHistoryInterface;
use App\Models\WordHistory;
use App\Repositories\{
    BaseRepository
};

/**
 * Repositorio para manipulação de dados do usuario da api.
 */
class WordHistoryRepository extends BaseRepository implements WordHistoryInterface
{
    public function __construct(protected WordHistory $wordHistory) {
        parent::__construct($wordHistory);
    }
}
