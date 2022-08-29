<?php

namespace Alex\Weblog\UnitTests\Commands;

use Alex\Weblog\Blog\Commands\Users\CreateUser;
use Alex\Weblog\Exceptions\UserNotFoundException;
use Alex\Weblog\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\Blog\Entities\UUID;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class CreateUserTest extends TestCase
{
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
        $command = new CreateUser($this->makeUsersRepository());
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "first_name, last_name, password").');

        $command->run(
            new ArrayInput([
                'username' => 'user1',
            ]),
            new NullOutput()
        );
    }

    public function testItRequiresFirstName(): void
    {
        $command = new CreateUser($this->makeUsersRepository());
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "first_name, last_name").');

        $command->run(
            new ArrayInput([
                'username' => 'user1',
                'password' => 'some_password'
            ]),
            new NullOutput()
        );
    }

    public function testItRequiresLastName(): void
    {
        $command = new CreateUser($this->makeUsersRepository());
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "last_name").');

        $command->run(
            new ArrayInput([
                'username' => 'user1',
                'password' => 'some_password',
                'first_name' => 'Ivan',
            ]),
            new NullOutput()
        );
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

        $command = new CreateUser($usersRepository);

        $command->run(
            new ArrayInput([
                'username' => 'user1',
                'password' => 'some_password',
                'first_name' => 'Ivan',
                'last_name' => 'Nikitin',
            ]),
            new NullOutput()
        );

        $this->assertTrue($usersRepository->wasCalled());
    }
}
