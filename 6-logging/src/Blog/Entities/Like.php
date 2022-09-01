<?php

namespace Alex\Weblog\Blog\Entities;

use Alex\Weblog\Blog\Entities\UUID;
use Alex\Weblog\Blog\Entities\Post;
use Alex\Weblog\Blog\Entities\User;

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
