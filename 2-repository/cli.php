<?php

use Alex\Weblog\Blog\Commands\Arguments;
use Alex\Weblog\Blog\Commands\CreateUserCommand;
use Alex\Weblog\Blog\Repositories\ArticlesRepositories\SqliteArticlesRepository;
use Alex\Weblog\Blog\Repositories\CommentsRepositories\SqliteCommentsRepository;
use Alex\Weblog\Blog\Repositories\UsersRepositories\SqliteUsersRepository;
use Alex\Weblog\Blog\Repositories\UsersRepositories\InMemoryUsersRepository;
use Alex\Weblog\Blog\Article;
use Alex\Weblog\Blog\Comment;
use Alex\Weblog\Blog\User;
use Alex\Weblog\Blog\UUID;

require_once __DIR__ . '/vendor/autoload.php';

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

// $articlesRepository = new SqliteArticlesRepository($connection);

// $commentsRepository = new SqliteCommentsRepository($connection);

// $usersRepository = new SqliteUsersRepository($connection);
// $usersRepository = new InMemoryUsersRepository();

try {
    // $command = new CreateUserCommand($usersRepository);
    // $command->handle(Arguments::fromArgv($argv));

    // $commentsRepository->save(new Comment(UUID::random(), UUID::random(), UUID::random(), 'comment'));
    // echo $commentsRepository->get(new UUID('1d4b06c5-9618-4699-8869-2aced205241e'));

    // $articlesRepository->save(new Article(UUID::random(), UUID::random(), 'title', 'text'));
    // echo $articlesRepository->get(new UUID('20310bfc-b6c3-4c3e-937e-b6dea8bad2c5'));

    // $usersRepository->save(new User(UUID::random(), 'admin', 'Ivan', 'Petrov'));
    // print $usersRepository->getByUsername('ivan');
} catch (Exception $exception) {
    echo $exception->getMessage();
}
