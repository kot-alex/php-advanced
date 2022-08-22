<?php

namespace Alex\Weblog\Blog\Repositories\Interfaces;

use Alex\Weblog\Blog\Like;
use Alex\Weblog\Blog\UUID;

interface LikesRepositoryInterface
{
    public function save(Like $like): void;
    public function getByPostUuid(UUID $PostUuid): array;
    public function checkDuplicate(UUID $authorUuid, UUID $postUuid): void;
}
