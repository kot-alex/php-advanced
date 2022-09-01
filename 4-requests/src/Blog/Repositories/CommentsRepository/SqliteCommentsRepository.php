<?php

namespace Alex\Weblog\Blog\Repositories\CommentsRepository;

use Alex\Weblog\Exceptions\CommentNotFoundException;
use Alex\Weblog\Blog\Repositories\interfaces\CommentsRepositoryInterface;
use Alex\Weblog\Blog\Comment;
use Alex\Weblog\Blog\Post;
use Alex\Weblog\Blog\User;
use Alex\Weblog\Blog\UUID;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{
    public function __construct(private \PDO $connection)
    {
    }

    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            "INSERT INTO comments (uuid, post_uuid, author_uuid, text) 
            VALUES (:uuid, :post_uuid, :author_uuid, :text)"
        );

        $statement->execute([
            ':uuid' => (string)$comment->uuid(),
            ':post_uuid' => (string)$comment->post()->uuid(),
            ':author_uuid' => (string)$comment->author()->uuid(),
            ':text' => $comment->text(),
        ]);
    }

    public function get(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare(
            "SELECT * 
                FROM users
                LEFT JOIN posts
                ON users.uuid = posts.author_uuid
                LEFT JOIN comments
                ON posts.uuid = comments.post_uuid
                WHERE comments.uuid = :uuid"
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
            throw new CommentNotFoundException("Cannot find comment: $uuid");
        }

        $user = new User(
            new UUID($result['author_uuid']),
            $result['username'],
            $result['first_name'],
            $result['last_name']
        );

        $post = new Post(
            new UUID($result['post_uuid']),
            $user,
            $result['title'],
            $result['text']
        );

        return new Comment(
            new UUID($result['uuid']),
            $post,
            $user,
            $result['text']
        );
    }
}
