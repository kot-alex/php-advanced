<?php

namespace Alex\Weblog\Blog\Repositories\Interfaces;

use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\Blog\Entities\UUID;

interface UsersRepositoryInterface
{
    public function save(User $user): void;
    public function get(UUID $uuid): User;
    public function getByUsername(string $username): User;
    public function usernameExists(string $username): void;
    public function deleteByUsername(string $username): void;
}
