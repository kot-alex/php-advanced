<?php

namespace Alex\Weblog\Blog\Repositories\PostsRepository;

use Alex\Weblog\Blog\Exceptions\PostNotFoundException;
use Alex\Weblog\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use Alex\Weblog\Blog\Post;
use Alex\Weblog\Blog\UUID;

class SqlitePostsRepository implements PostsRepositoryInterface
{
    public function __construct(private \PDO $connection)
    {
    }

    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            "INSERT INTO posts (uuid, author_uuid, title, text) 
            VALUES (:uuid, :author_uuid, :title, :text)"
        );

        $statement->execute([
            ':uuid' => (string)$post->uuid(),
            ':author_uuid' => (string)$post->authorUuid(),
            ':title' => $post->title(),
            ':text' => $post->text(),
        ]);
    }

    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare(
            "SELECT * FROM posts WHERE uuid = :uuid"
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
            throw new PostNotFoundException("Cannot find post: $uuid");
        }
        return new Post(
            new UUID($result['uuid']),
            new UUID($result['author_uuid']),
            $result['title'],
            $result['text']
        );
    }
}
