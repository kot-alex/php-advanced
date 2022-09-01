<?php

namespace Alex\Weblog\Blog\Commands;

use Alex\Weblog\Exceptions\UsernameAlreadyExistsException;
use Alex\Weblog\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\Blog\Entities\UUID;
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

        $uuid = UUID::random();

        $this->usersRepository->save(new User(
            $uuid,
            $username,
            $arguments->get('first_name'),
            $arguments->get('last_name'),
        ));
    }
}
