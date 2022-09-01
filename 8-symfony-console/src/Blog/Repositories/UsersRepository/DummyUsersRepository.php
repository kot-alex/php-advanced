<?php

namespace Alex\Weblog\Blog\Repositories\UsersRepository;

use Alex\Weblog\Exceptions\UserNotFoundException;
use Alex\Weblog\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\Blog\Entities\UUID;

class DummyUsersRepository implements UsersRepositoryInterface
{
    public function save(User $user): void
    {
        // 
    }

    public function get(UUID $uuid): User
    {
        throw new UserNotFoundException('Not found');
    }

    public function getByUsername(string $username): User
    {
        return new User(UUID::random(), 'user1', 'pass1', 'first_name', 'last_name');
    }

    public function usernameExists(string $username): void
    {
        //
    }

    public function deleteByUsername(string $username): void
    {
        // 
    }
}
