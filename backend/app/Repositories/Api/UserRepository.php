<?php

namespace App\Repositories\Api;

use App\Interfaces\UserInterface;
use App\Models\User;
use App\Repositories\{
    BaseRepository
};

/**
 * Repositorio para manipulação de dados do usuario da api.
 */
class UserRepository extends BaseRepository implements UserInterface
{
    public function __construct(protected User $user) {
        parent::__construct($user);
    }
}
