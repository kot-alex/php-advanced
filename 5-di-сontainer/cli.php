<?php

use Alex\Weblog\Blog\Commands\CreateUserCommand;
use Alex\Weblog\Blog\Commands\Arguments;
use Alex\Weblog\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Alex\Weblog\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Alex\Weblog\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Alex\Weblog\Blog\UUID;
use Alex\Weblog\Blog\User;
use Alex\Weblog\Blog\Post;
use Alex\Weblog\Blog\Comment;

$container = require __DIR__ . '/bootstrap.php';

// $command = $container->get(SqliteUsersRepository::class);

// $command = $container->get(SqlitePostsRepository::class);

// $command = $container->get(SqliteCommentsRepository::class);

try {
    // $command = $container->get(CreateUserCommand::class);       // php cli.php username=user1 first_name=Petr last_name=Petrov
    // $command->handle(Arguments::fromArgv($argv));

    // $command->save(new User(UUID::random(), 'admin', 'Ivan', 'Ivanov'));
    // print $command->getByUsername('admin');

    // $command->save(new Post(UUID::random(), new User(UUID::random(), 'username', 'firstname', 'lastname'), 'some_title', 'some_post'));
    // echo $command->get(new UUID('a9c2ca36-13b3-48ff-a82d-7e0953cff985'));

    // $command->save(new Comment(UUID::random(), new Post(UUID::random(), new User(UUID::random(), 'username', 'firstname', 'lastname'), 'firstname', 'lastname'), new User(UUID::random(), 'username', 'firstname', 'lastname'), 'some_comment'));
    // echo $command->get(new UUID('b17556ec-2daa-4f86-a593-f91c8df817a3'));
} catch (Exception $exception) {
    echo $exception->getMessage();
}
