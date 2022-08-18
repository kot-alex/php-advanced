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

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

// $commentsRepository = new SqliteCommentsRepository($connection);

// $postsRepository = new SqlitePostsRepository($connection);

// $usersRepository = new SqliteUsersRepository($connection);
// $usersRepository = new InMemoryUsersRepository();

try {
    // $command = new CreateUserCommand($usersRepository);
    // $command->handle(Arguments::fromArgv($argv));

    // $commentsRepository->save(new Comment(UUID::random(), new Post(UUID::random(), new User(UUID::random(), 'username', 'firstname', 'lastname'), 'firstname', 'lastname'), new User(UUID::random(), 'username', 'firstname', 'lastname'), 'comment'));
    // echo $commentsRepository->get(new UUID('3948963a-5458-44e7-9db1-4fd93d084d3d'));

    // $postsRepository->save(new Post(UUID::random(), new User(UUID::random(), 'username', 'firstname', 'lastname'), 'title', 'post'));
    // echo $postsRepository->get(new UUID('a9c2ca36-13b3-48ff-a82d-7e0953cff985'));

    // $usersRepository->save(new User(UUID::random(), 'admin', 'Ivan', 'Petrov'));
    // print $usersRepository->getByUsername('ivan');
} catch (Exception $exception) {
    echo $exception->getMessage();
}
