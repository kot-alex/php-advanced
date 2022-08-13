<?php

namespace Alex\Weblog\Blog;

use Alex\Weblog\Blog\UUID;

class Comment
{
    public function __construct(
        private UUID $uuid,
        private UUID $postUuid,
        private UUID $authorUuid,
        private string $text
    ) {
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function postUuid(): UUID
    {
        return $this->postUuid;
    }

    public function authorUuid(): UUID
    {
        return $this->authorUuid;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function __toString()
    {
        return $this->text;
    }
}
