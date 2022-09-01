<?php

namespace Alex\Weblog\Blog\Repositories\UsersRepository;

use Alex\Weblog\Exceptions\UserNotFoundException;
use Alex\Weblog\Exceptions\UsernameAlreadyExistsException;
use Alex\Weblog\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use Alex\Weblog\Blog\Entities\User;
use Alex\Weblog\Blog\Entities\UUID;
use Psr\Log\LoggerInterface;

class SqliteUsersRepository implements UsersRepositoryInterface
{
    public function __construct(
        private \PDO $connection,
        private LoggerInterface $logger,
    ) {
    }

    public function save(User $user): void
    {
        $statement = $this->connection->prepare(
            "INSERT INTO users (uuid, username, password, first_name, last_name) 
            VALUES (:uuid, :username, :password, :first_name, :last_name)"
        );

        $statement->execute([
            ':uuid' => (string)$user->uuid(),
            ':username' => $user->username(),
            ':password' => $user->hashedPassword(),
            ':first_name' => $user->firstName(),
            ':last_name' => $user->lastName(),
        ]);

        $this->logger->info("User created: " . $user->uuid());
    }

    public function get(UUID $uuid): User
    {
        $statement = $this->connection->prepare(
            "SELECT * FROM users WHERE uuid = :uuid"
        );

        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);

        return $this->getUser($statement, $uuid);
    }

    public function getByUsername(string $username): User
    {
        $statement = $this->connection->prepare(
            "SELECT * FROM users WHERE username = :username"
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
            $this->logger->warning("Cannot find user: $username");
            throw new UserNotFoundException("Cannot find user: $username");
        }

        return new User(
            new UUID($result['uuid']),
            $result['username'],
            $result['password'],
            $result['first_name'],
            $result['last_name']
        );
    }

    public function usernameExists(string $username): void
    {
        $statement = $this->connection->prepare(
            "SELECT * FROM users WHERE username = :username"
        );

        $statement->execute([
            ':username' => $username,
        ]);

        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            $this->logger->warning("Username already exists: $username");
            throw new UsernameAlreadyExistsException("Username already exists: $username");
        }
    }

    public function deleteByUsername(string $username): void
    {
        $statement = $this->connection->prepare(
            "DELETE FROM users WHERE username = :username"
        );

        $statement->execute([
            ':username' => $username,
        ]);

        $this->logger->info("User deleted: $username");
    }
}
