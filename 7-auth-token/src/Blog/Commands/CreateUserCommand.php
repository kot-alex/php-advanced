<?php

namespace Alex\Weblog\Blog\Commands;

use Alex\Weblog\Exceptions\UsernameAlreadyExistsException;
use Alex\Weblog\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use Alex\Weblog\Blog\Entities\User;
use Psr\Log\LoggerInterface;

class CreateUserCommand
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger
    ) {
    }

    public function handle(Arguments $arguments): void
    {
        $this->logger->info("Create user command started");

        $username = $arguments->get('username');

        if ($this->usersRepository->usernameExists($username)) {
            throw new UsernameAlreadyExistsException;
        }

        $user = User::createFrom(
            $username,
            $arguments->get('password'),
            $arguments->get('first_name'),
            $arguments->get('last_name')
        );

        $this->usersRepository->save($user);
    }
}
