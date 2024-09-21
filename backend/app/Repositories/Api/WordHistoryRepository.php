<?php

namespace App\Repositories\Api;

use App\Interfaces\Api\WordHistoryRepositoryInterface;
use App\Models\WordHistory;
use App\Repositories\{
    BaseRepository
};

/**
 * Repositorio para manipulação de dados de historico da api.
 */
class WordHistoryRepository extends BaseRepository implements WordHistoryRepositoryInterface
{
    public function __construct(protected WordHistory $wordHistory) {
        parent::__construct($wordHistory);
    }
}
