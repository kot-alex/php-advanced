<?php

use Alex\Weblog\Blog\Commands\Arguments;
use Alex\Weblog\Blog\Commands\CreateUserCommand;
use Alex\Weblog\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Alex\Weblog\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Alex\Weblog\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Alex\Weblog\Blog\Repositories\UsersRepository\InMemoryUsersRepository;
use Alex\Weblog\Blog\Comment;
use Alex\Weblog\Blog\Post;
use Alex\Weblog\Blog\User;
use Alex\Weblog\Blog\UUID;

require_once __DIR__ . '/vendor/autoload.php';

// $connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

// $commentsRepository = new SqliteCommentsRepository($connection);

// $postsRepository = new SqlitePostsRepository($connection);

// $usersRepository = new SqliteUsersRepository($connection);
// $usersRepository = new InMemoryUsersRepository();

try {
    // $command = new CreateUserCommand($usersRepository);
    // $command->handle(Arguments::fromArgv($argv));

    // $commentsRepository->save(new Comment(UUID::random(), UUID::random(), UUID::random(), 'comment'));
    // echo $commentsRepository->get(new UUID('6786ac95-0497-44b8-a657-4dc2ae8fb382'));

    // $postsRepository->save(new Post(UUID::random(), UUID::random(), 'title', 'text'));
    // echo $postsRepository->get(new UUID('f05d8f1c-2bf4-49b4-a6ed-bd935c3b8440'));

    // $usersRepository->save(new User(UUID::random(), 'admin', 'Ivan', 'Petrov'));
    // print $usersRepository->getByUsername('ivan');
} catch (Exception $exception) {
    echo $exception->getMessage();
}
