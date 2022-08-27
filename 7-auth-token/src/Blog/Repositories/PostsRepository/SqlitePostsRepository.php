<?php

namespace Alex\Weblog\Blog\Repositories\PostsRepository;

use Alex\Weblog\Exceptions\PostNotFoundException;
use Alex\Weblog\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use Alex\Weblog\Blog\Entities\Post;
use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\Blog\Entities\UUID;
use Psr\Log\LoggerInterface;

class SqlitePostsRepository implements PostsRepositoryInterface
{
    public function __construct(
        private \PDO $connection,
        private LoggerInterface $logger
    ) {
    }

    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            "INSERT INTO posts (uuid, author_uuid, title, text)
            VALUES (:uuid, :author_uuid, :title, :text)"
        );

        $statement->execute([
            ':uuid' => (string)$post->uuid(),
            ':author_uuid' => (string)$post->user()->uuid(),
            ':title' => $post->title(),
            ':text' => $post->text(),
        ]);

        $this->logger->info("Post created: " . $post->uuid());
    }

    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare(
            "SELECT users.uuid AS author_uuid,
                    username,
                    password,
                    first_name,
                    last_name,
                    posts.uuid,
                    title,
                    text
            FROM    users
                    LEFT JOIN posts
                    ON users.uuid = posts.author_uuid
            WHERE   posts.uuid = :uuid"
        );

        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);

        return $this->getPost($statement, $uuid);
    }

    private function getPost(\PDOStatement $statement, string $uuid): Post
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($result === false) {
            $this->logger->warning("Cannot find post: $uuid");
            throw new PostNotFoundException("Cannot find post: $uuid");
        }

        $user = new User(
            new UUID($result['author_uuid']),
            $result['username'],
            $result['password'],
            $result['first_name'],
            $result['last_name']
        );

        return new Post(
            new UUID($result['uuid']),
            $user,
            $result['title'],
            $result['text']
        );
    }

    public function deleteByUuid(UUID $uuid): void
    {
        $statement = $this->connection->prepare(
            "DELETE FROM posts WHERE uuid = :uuid"
        );

        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);

        $this->logger->info("Post deleted: $uuid");
    }
}
