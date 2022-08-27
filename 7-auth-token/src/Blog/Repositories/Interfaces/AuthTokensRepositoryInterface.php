<?php

namespace Alex\Weblog\Blog\Repositories\Interfaces;

use Alex\Weblog\Blog\Entities\AuthToken;
use Alex\Weblog\Blog\Entities\User;

interface AuthTokensRepositoryInterface
{
    public function save(AuthToken $authToken): void;
    public function get(string $token): AuthToken;
    public function getByUser(User $user): AuthToken;
}
