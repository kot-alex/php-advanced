<?php

namespace Alex\Weblog\UnitTests\Repositories;

use Alex\Weblog\Exceptions\CommentNotFoundException;
use Alex\Weblog\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Alex\Weblog\Blog\Entities\UUID;
use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\Blog\Entities\Post;
use Alex\Weblog\Blog\Entities\Comment;
use Alex\Weblog\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;

class SqliteCommentsRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenCommentNotFound(): void
    {
        $connectionStub = $this->createStub(\PDO::class);
        $statementStub = $this->createStub(\PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);
        $connectionStub->method('prepare')->willReturn($statementStub);

        $repository = new SqliteCommentsRepository($connectionStub, new DummyLogger());
        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage('Cannot find comment: 6786ac95-0497-44b8-a657-4dc2ae8fb382');

        $repository->get(new UUID('6786ac95-0497-44b8-a657-4dc2ae8fb382'));
    }

    public function testItSavesCommentToDatabase(): void
    {
        $connectionStub = $this->createStub(\PDO::class);
        $statementMock = $this->createMock(\PDOStatement::class);
        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':uuid' => '6786ac95-0497-44b8-a657-4dc2ae8fb382',
                ':post_uuid' => 'ada3b16d-70df-42ba-a3f2-1c04c45f498f',
                ':author_uuid' => 'd0047131-f854-4812-831f-4b2b8cfb3bf1',
                ':text' => 'some_comment',
            ]);
        $connectionStub->method('prepare')->willReturn($statementMock);
        $repository = new SqliteCommentsRepository($connectionStub, new DummyLogger());

        $user = new User(
            new UUID('d0047131-f854-4812-831f-4b2b8cfb3bf1'),
            'username',
            'first_name',
            'last_name'
        );

        $post = new Post(
            new UUID('ada3b16d-70df-42ba-a3f2-1c04c45f498f'),
            $user,
            'some_title',
            'some_text'
        );

        $repository->save(
            new Comment(
                new UUID('6786ac95-0497-44b8-a657-4dc2ae8fb382'),
                $post,
                $user,
                'some_comment'
            )
        );
    }
}
