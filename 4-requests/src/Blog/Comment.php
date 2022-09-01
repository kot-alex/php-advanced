<?php

namespace Alex\Weblog\Blog;

use Alex\Weblog\Blog\UUID;
use Alex\Weblog\Blog\Post;
use Alex\Weblog\Blog\User;

class Comment
{
    public function __construct(
        private UUID $uuid,
        private Post $post,
        private User $author,
        private string $text
    ) {
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function post(): Post
    {
        return $this->post;
    }

    public function author(): User
    {
        return $this->author;
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
