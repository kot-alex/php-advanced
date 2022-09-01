<?php

namespace Alex\Weblog\Blog\Repositories\Interfaces;

use Alex\Weblog\Blog\Entities\Comment;
use Alex\Weblog\Blog\Entities\UUID;

interface CommentsRepositoryInterface
{
    public function save(Comment $comment): void;
    public function get(UUID $uuid): Comment;
}
