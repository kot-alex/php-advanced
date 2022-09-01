<?php

namespace Alex\Weblog\Blog\Entities;

use Alex\Weblog\Blog\Entities\UUID;
use Alex\Weblog\Blog\Entities\User;

class Post
{
    public function __construct(
        private UUID $uuid,
        private User $user,
        private string $title,
        private string $text
    ) {
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function __toString()
    {
        return $this->title . ' >>> ' . $this->text;
    }
}
