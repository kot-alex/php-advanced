<?php

namespace Alex\Weblog\Blog;

use Alex\Weblog\Blog\UUID;
use Alex\Weblog\Blog\Post;
use Alex\Weblog\Blog\User;

class Like
{
    public function __construct(
        private UUID $uuid,
        private Post $post,
        private User $author,
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
}
