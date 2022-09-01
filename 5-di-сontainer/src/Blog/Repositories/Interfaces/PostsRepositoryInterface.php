<?php

namespace Alex\Weblog\Blog\Repositories\Interfaces;

use Alex\Weblog\Blog\Post;
use Alex\Weblog\Blog\UUID;

interface PostsRepositoryInterface
{
    public function save(Post $post): void;
    public function get(UUID $uuid): Post;
    public function deleteByUuid(UUID $uuid): void;
}
