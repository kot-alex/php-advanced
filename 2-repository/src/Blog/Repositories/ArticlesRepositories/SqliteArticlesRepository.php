<?php

namespace Alex\Weblog\Blog\Repositories\ArticlesRepositories;

use Alex\Weblog\Blog\Exceptions\ArticleNotFoundException;
use Alex\Weblog\Blog\Repositories\ArticlesRepositories\ArticlesRepositoryInterface;
use Alex\Weblog\Blog\Article;
use Alex\Weblog\Blog\UUID;

class SqliteArticlesRepository implements ArticlesRepositoryInterface
{
    public function __construct(private \PDO $connection)
    {
    }

    public function save(Article $article): void
    {
        $statement = $this->connection->prepare(
            "INSERT INTO articles (uuid, author_uuid, title, text) 
            VALUES (:uuid, :author_uuid, :title, :text)"
        );

        $statement->execute([
            ':uuid' => (string)$article->uuid(),
            ':author_uuid' => (string)$article->authorUuid(),
            ':title' => $article->title(),
            ':text' => $article->text(),
        ]);
    }

    public function get(UUID $uuid): Article
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM articles WHERE uuid = :uuid'
        );

        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);

        return $this->getArticle($statement, $uuid);
    }

    private function getArticle(\PDOStatement $statement, string $uuid): Article
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new ArticleNotFoundException(
                "Cannot find article: $uuid"
            );
        }
        return new Article(
            new UUID($result['uuid']),
            new UUID($result['author_uuid']),
            $result['title'],
            $result['text']
        );
    }
}
