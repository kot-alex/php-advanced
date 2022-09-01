<?php

namespace Alex\Weblog\Blog\Repositories\UsersRepositories;

use Alex\Weblog\Blog\Exceptions\UserNotFoundException;
use Alex\Weblog\Blog\Repositories\UsersRepositories\UsersRepositoryInterface;
use Alex\Weblog\Blog\User;
use Alex\Weblog\Blog\UUID;

class SqliteUsersRepository implements UsersRepositoryInterface
{
    public function __construct(private \PDO $connection)
    {
    }

    public function save(User $user): void
    {
        $statement = $this->connection->prepare(
            "INSERT INTO users (uuid, username, first_name, last_name) 
            VALUES (:uuid, :username, :first_name, :last_name)"
        );

        $statement->execute([
            ':uuid' => (string)$user->uuid(),
            ':username' => $user->username(),
            ':first_name' => $user->firstName(),
            ':last_name' => $user->lastName(),
        ]);
    }

    public function get(UUID $uuid): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE uuid = :uuid'
        );

        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);

        return $this->getUser($statement, $uuid);
    }

    public function getByUsername(string $username): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE username = :username'
        );

        $statement->execute([
            ':username' => $username,
        ]);

        return $this->getUser($statement, $username);
    }

    private function getUser(\PDOStatement $statement, string $username): User
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new UserNotFoundException(
                "Cannot find user: $username"
            );
        }
        return new User(
            new UUID($result['uuid']),
            $result['username'],
            $result['first_name'],
            $result['last_name']
        );
    }
}
