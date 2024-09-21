<?php

namespace App\Repositories\Api;

use App\Interfaces\Api\WordFavoriteRepositoryInterface;
use App\Models\WordFavorite;
use App\Repositories\{
    BaseRepository
};

/**
 * Repositorio para manipulação de dados de favoritos da api.
 */
class WordFavorityRepository extends BaseRepository implements WordFavoriteRepositoryInterface
{
    public function __construct(protected WordFavorite $wordFavorite) {
        parent::__construct($wordFavorite);
    }
}
