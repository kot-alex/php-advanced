<?php

namespace Alex\Weblog\Blog\Repositories\ArticlesRepositories;

use Alex\Weblog\Blog\Article;
use Alex\Weblog\Blog\UUID;

interface ArticlesRepositoryInterface
{
    public function save(Article $article): void;
    public function get(UUID $uuid): Article;
}
