<?php

namespace Alex\Weblog\Blog\Repositories\LikesRepository;

use Alex\Weblog\Exceptions\PostNotFoundException;
use Alex\Weblog\Exceptions\LikeAlreadyExistsException;
use Alex\Weblog\Blog\Repositories\interfaces\LikesRepositoryInterface;
use Alex\Weblog\Blog\Like;
use Alex\Weblog\Blog\UUID;

class SqliteLikesRepository implements LikesRepositoryInterface
{
    public function __construct(private \PDO $connection)
    {
    }

    public function save(Like $like): void
    {
        $statement = $this->connection->prepare(
            "INSERT INTO likes (uuid, post_uuid, author_uuid) 
            VALUES (:uuid, :post_uuid, :author_uuid)"
        );

        $statement->execute([
            ':uuid' => (string)$like->uuid(),
            ':post_uuid' => (string)$like->post()->uuid(),
            ':author_uuid' => (string)$like->author()->uuid()
        ]);
    }

    public function getByPostUuid(UUID $postUuid): array
    {
        $statement = $this->connection->prepare(
            "SELECT uuid
                FROM likes
                WHERE post_uuid = :uuid"
        );

        $statement->execute([
            ':uuid' => (string)$postUuid,
        ]);

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if (!$result) {
            throw new PostNotFoundException("Cannot find post: $postUuid");
        }

        return $result;
    }

    public function checkDuplicate(UUID $authorUuid, UUID $postUuid): void
    {
        $statement = $this->connection->prepare(
            "SELECT *
                FROM likes
                WHERE author_uuid = :author_uuid AND
                        post_uuid = :post_uuid"
        );

        $statement->execute([
            ':author_uuid' => (string)$authorUuid,
            ':post_uuid' => (string)$postUuid
        ]);

        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            throw new LikeAlreadyExistsException("User $authorUuid already liked post $postUuid");
        }
    }
}
