<?php

namespace Alex\Weblog\UnitTests\Repositories;

use Alex\Weblog\Exceptions\UserNotFoundException;
use Alex\Weblog\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\Blog\Entities\UUID;
use Alex\Weblog\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;

class SqliteUsersRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenUserNotFound(): void
    {
        $connectionStub = $this->createStub(\PDO::class);
        $statementStub = $this->createStub(\PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);
        $connectionStub->method('prepare')->willReturn($statementStub);

        $repository = new SqliteUsersRepository($connectionStub, new DummyLogger());
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('Cannot find user: ivan');

        $repository->getByUsername('ivan');
    }

    public function testItSavesUserToDatabase(): void
    {
        $connectionStub = $this->createStub(\PDO::class);
        $statementMock = $this->createMock(\PDOStatement::class);
        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
                ':username' => 'user1',
                ':password' => 'pass123',
                ':first_name' => 'Ivan',
                ':last_name' => 'Ivanov',
            ]);
        $connectionStub->method('prepare')->willReturn($statementMock);
        $repository = new SqliteUsersRepository($connectionStub, new DummyLogger());

        $repository->save(
            new User(
                new UUID('123e4567-e89b-12d3-a456-426614174000'),
                'user1',
                'pass123',
                'Ivan',
                'Ivanov'
            )
        );
    }
}
