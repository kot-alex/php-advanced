<?php

namespace Alex\Weblog\Blog\Repositories\CommentsRepositories;

use Alex\Weblog\Blog\Exceptions\CommentNotFoundException;
use Alex\Weblog\Blog\Repositories\CommentsRepositories\CommentsRepositoryInterface;
use Alex\Weblog\Blog\Comment;
use Alex\Weblog\Blog\UUID;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{
    public function __construct(private \PDO $connection)
    {
    }

    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            "INSERT INTO comments (uuid, article_uuid, author_uuid, text) 
            VALUES (:uuid, :article_uuid, :author_uuid, :text)"
        );

        $statement->execute([
            ':uuid' => (string)$comment->uuid(),
            ':article_uuid' => (string)$comment->articleUuid(),
            ':author_uuid' => (string)$comment->authorUuid(),
            ':text' => $comment->text(),
        ]);
    }

    public function get(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE uuid = :uuid'
        );

        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);

        return $this->getComment($statement, $uuid);
    }

    private function getComment(\PDOStatement $statement, string $uuid): Comment
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new CommentNotFoundException(
                "Cannot find comment: $uuid"
            );
        }
        return new Comment(
            new UUID($result['uuid']),
            new UUID($result['article_uuid']),
            new UUID($result['author_uuid']),
            $result['text']
        );
    }
}
