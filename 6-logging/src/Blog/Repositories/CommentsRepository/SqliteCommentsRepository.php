<?php

namespace Alex\Weblog\Blog\Repositories\CommentsRepository;

use Alex\Weblog\Exceptions\CommentNotFoundException;
use Alex\Weblog\Blog\Repositories\interfaces\CommentsRepositoryInterface;
use Alex\Weblog\Blog\Entities\Comment;
use Alex\Weblog\Blog\Entities\Post;
use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\Blog\Entities\UUID;
use Psr\Log\LoggerInterface;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{
    public function __construct(
        private \PDO $connection,
        private LoggerInterface $logger,
    ) {
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

        $commentUuid = $comment->uuid();

        $this->logger->info("Comment created: $commentUuid");
    }

    public function get(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare(
            "SELECT users.uuid AS author_uuid,
                    username,
                    first_name,
                    last_name,
                    posts.uuid AS post_uuid,
                    title,
                    posts.text AS post,
                    comments.uuid,
                    comments.text AS comment
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
            $this->logger->warning("Cannot find comment: $uuid");
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
            $result['post']
        );

        return new Comment(
            new UUID($result['uuid']),
            $post,
            $user,
            $result['comment']
        );
    }
}
