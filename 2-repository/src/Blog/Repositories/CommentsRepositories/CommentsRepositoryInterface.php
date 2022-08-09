<?php

namespace Alex\Weblog\Blog\Repositories\CommentsRepositories;

use Alex\Weblog\Blog\Comment;
use Alex\Weblog\Blog\UUID;

interface CommentsRepositoryInterface
{
    public function save(Comment $comment): void;
    public function get(UUID $uuid): Comment;
}
