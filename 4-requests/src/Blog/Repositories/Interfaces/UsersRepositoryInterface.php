<?php

namespace Alex\Weblog\Blog\Repositories\Interfaces;

use Alex\Weblog\Blog\User;
use Alex\Weblog\Blog\UUID;

interface UsersRepositoryInterface
{
    public function save(User $user): void;
    public function get(UUID $uuid): User;
    public function getByUsername(string $username): User;
    public function deleteByUsername(string $username): void;
}
