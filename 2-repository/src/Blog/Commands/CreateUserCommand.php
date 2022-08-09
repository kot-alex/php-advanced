<?php

namespace Alex\Weblog\Blog\Commands;

use Alex\Weblog\Blog\Exceptions\CommandException;
use Alex\Weblog\Blog\Exceptions\UserNotFoundException;
use Alex\Weblog\Blog\Repositories\UsersRepositories\UsersRepositoryInterface;
use Alex\Weblog\Blog\User;
use Alex\Weblog\Blog\UUID;

class CreateUserCommand
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {
    }

    public function handle(Arguments $arguments): void
    {
        $username = $arguments->get('username');

        if ($this->userExists($username)) {
            throw new CommandException("User already exists: $username");
        }

        $this->usersRepository->save(new User(
            UUID::random(),
            $username,
            $arguments->get('first_name'),
            $arguments->get('last_name'),
        ));
    }

    private function userExists(string $username): bool
    {
        try {
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }
}
