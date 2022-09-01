<?php

namespace Alex\Weblog\Blog\Entities;

class User
{
    public function __construct(
        private UUID $uuid,
        private string $username,
        private string $hashedPassword,
        private string $firstName,
        private string $lastName
    ) {
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function hashedPassword(): string
    {
        return $this->hashedPassword;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function __toString()
    {
        return $this->username;
    }

    private static function hash(string $password, UUID $uuid): string
    {
        return hash('sha256', $uuid . $password);
    }

    public function checkPassword(string $password): bool
    {
        return $this->hashedPassword === self::hash($password, $this->uuid);
    }

    public static function createFrom(
        string $username,
        string $password,
        string $firstName,
        string $lastName
    ): self {
        $uuid = UUID::random();
        return new self(
            $uuid,
            $username,
            self::hash($password, $uuid),
            $firstName,
            $lastName
        );
    }
}
