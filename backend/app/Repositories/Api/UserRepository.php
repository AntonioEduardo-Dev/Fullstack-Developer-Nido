<?php

namespace App\Repositories\Api;

use App\Interfaces\Api\UserRepositoryInterface;
use App\Models\User;
use App\Repositories\{
    BaseRepository
};

/**
 * Repositorio para manipulação de dados do usuario da api.
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(protected User $user) {
        parent::__construct($user);
    }
}
