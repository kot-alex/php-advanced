<?php

namespace Alex\Weblog\UnitTests\Commands;

use Alex\Weblog\Blog\Commands\Arguments;
use Alex\Weblog\Blog\Commands\CreateUserCommand;
use Alex\Weblog\Exceptions\ArgumentsException;
use Alex\Weblog\Exceptions\UserNotFoundException;
use Alex\Weblog\Exceptions\UsernameAlreadyExistsException;
use Alex\Weblog\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use Alex\Weblog\Blog\Repositories\UsersRepository\DummyUsersRepository;
use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\Blog\Entities\UUID;
use Alex\Weblog\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{
    public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
    {
        $command = new CreateUserCommand(new DummyUsersRepository(), new DummyLogger());
        $this->expectException(UsernameAlreadyExistsException::class);
        $this->expectExceptionMessage('Username already exists: user1');

        $command->handle(new Arguments(['username' => 'user1']));
    }

    private function makeUsersRepository(): UsersRepositoryInterface
    {
        return new class implements UsersRepositoryInterface
        {
            public function save(User $user): void
            {
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException('Not found');
            }

            public function getByUsername(string $username): User
            {
                throw new UserNotFoundException('Not found');
            }

            public function usernameExists(string $username): void
            {
                // 
            }

            public function deleteByUsername(string $username): void
            {
                // 
            }
        };
    }

    public function testItRequiresPassword(): void
    {
        $command = new CreateUserCommand($this->makeUsersRepository(), new DummyLogger());
        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage('No such argument: password');

        $command->handle(new Arguments([
            'username' => 'user1',
        ]));
    }

    public function testItRequiresFirstName(): void
    {
        $command = new CreateUserCommand($this->makeUsersRepository(), new DummyLogger());
        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage('No such argument: first_name');

        $command->handle(new Arguments([
            'username' => 'user1',
            'password' => 'pass123'
        ]));
    }

    public function testItRequiresLastName(): void
    {
        $command = new CreateUserCommand($this->makeUsersRepository(), new DummyLogger());
        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage('No such argument: last_name');

        $command->handle(new Arguments([
            'username' => 'user1',
            'password' => 'pass123',
            'first_name' => 'Ivan',
        ]));
    }

    public function testItSavesUserToRepository(): void
    {
        $usersRepository = new class implements UsersRepositoryInterface
        {
            private bool $called = false;

            public function save(User $user): void
            {
                $this->called = true;
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException('Not found');
            }

            public function getByUsername(string $username): User
            {
                throw new UserNotFoundException('Not found');
            }

            public function usernameExists(string $username): void
            {
                // 
            }

            public function deleteByUsername(string $username): void
            {
                // 
            }

            public function wasCalled(): bool
            {
                return $this->called;
            }
        };

        $command = new CreateUserCommand($usersRepository, new DummyLogger());

        $command->handle(new Arguments([
            'username' => 'user1',
            'password' => 'pass123',
            'first_name' => 'Ivan',
            'last_name' => 'Ivanov',
        ]));

        $this->assertTrue($usersRepository->wasCalled());
    }
}
