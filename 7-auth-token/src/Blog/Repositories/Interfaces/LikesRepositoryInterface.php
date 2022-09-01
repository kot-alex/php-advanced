<?php

namespace Alex\Weblog\Blog\Repositories\Interfaces;

use Alex\Weblog\Blog\Entities\Like;
use Alex\Weblog\Blog\Entities\UUID;

interface LikesRepositoryInterface
{
    public function save(Like $like): void;
    public function getByPostUuid(UUID $PostUuid): array;
    public function checkDuplicate(UUID $authorUuid, UUID $postUuid): void;
}
