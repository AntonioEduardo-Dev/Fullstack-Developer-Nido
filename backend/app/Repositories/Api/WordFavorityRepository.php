<?php

namespace App\Repositories\Api;

use App\Interfaces\WordFavoriteInterface;
use App\Models\WordFavorite;
use App\Repositories\{
    BaseRepository
};

/**
 * Repositorio para manipulação de dados do usuario da api.
 */
class WordFavorityRepository extends BaseRepository implements WordFavoriteInterface
{
    public function __construct(protected WordFavorite $wordFavorite) {
        parent::__construct($wordFavorite);
    }
}
