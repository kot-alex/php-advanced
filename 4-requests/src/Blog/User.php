<?php

namespace Alex\Weblog\Blog;

use Alex\Weblog\Blog\UUID;

class User
{
    public function __construct(
        private UUID $uuid,
        private string $username,
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
        return $this->firstName . ' ' . $this->lastName;
    }
}
