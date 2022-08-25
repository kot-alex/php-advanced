<?php

namespace Alex\Weblog\UnitTests\Repositories;

use Alex\Weblog\Exceptions\PostNotFoundException;
use Alex\Weblog\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Alex\Weblog\Blog\Entities\Post;
use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\Blog\Entities\UUID;
use Alex\Weblog\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;

class SqlitePostsRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenPostNotFound(): void
    {
        $connectionStub = $this->createStub(\PDO::class);
        $statementStub = $this->createStub(\PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);
        $connectionStub->method('prepare')->willReturn($statementStub);

        $repository = new SqlitePostsRepository($connectionStub, new DummyLogger());
        $this->expectException(PostNotFoundException::class);
        $this->expectExceptionMessage('Cannot find post: f05d8f1c-2bf4-49b4-a6ed-bd935c3b8440');

        $repository->get(new UUID('f05d8f1c-2bf4-49b4-a6ed-bd935c3b8440'));
    }

    public function testItSavesPostToDatabase(): void
    {
        $connectionStub = $this->createStub(\PDO::class);
        $statementMock = $this->createMock(\PDOStatement::class);
        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':uuid' => 'f05d8f1c-2bf4-49b4-a6ed-bd935c3b8440',
                ':author_uuid' => 'c6c87174-2402-42f5-b728-c9bd0bc0ebe1',
                ':title' => 'some_title',
                ':text' => 'some_text',
            ]);
        $connectionStub->method('prepare')->willReturn($statementMock);
        $repository = new SqlitePostsRepository($connectionStub, new DummyLogger());

        $user = new User(
            new UUID('c6c87174-2402-42f5-b728-c9bd0bc0ebe1'),
            'username',
            'first_name',
            'last_name'
        );

        $repository->save(
            new Post(
                new UUID('f05d8f1c-2bf4-49b4-a6ed-bd935c3b8440'),
                $user,
                'some_title',
                'some_text'
            )
        );
    }
}
