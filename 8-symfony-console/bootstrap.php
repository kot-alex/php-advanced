<?php

use Alex\Weblog\Blog\Container\DIContainer;
use Alex\Weblog\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use Alex\Weblog\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use Alex\Weblog\Blog\Repositories\Interfaces\CommentsRepositoryInterface;
use Alex\Weblog\Blog\Repositories\Interfaces\LikesRepositoryInterface;
use Alex\Weblog\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Alex\Weblog\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Alex\Weblog\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Alex\Weblog\Blog\Repositories\LikesRepository\SqliteLikesRepository;
use Alex\Weblog\Blog\Repositories\Interfaces\AuthTokensRepositoryInterface;
use Alex\Weblog\Blog\Repositories\AuthTokensRepository\SqliteAuthTokensRepository;
use Alex\Weblog\http\Auth\PasswordAuthenticationInterface;
use Alex\Weblog\http\Auth\PasswordAuthentication;
use Alex\Weblog\http\Auth\TokenAuthenticationInterface;
use Alex\Weblog\http\Auth\TokenAuthentication;
use Dotenv\Dotenv;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Level;
use Monolog\Handler\StreamHandler;
use Faker\Generator;
use Faker\Provider\Lorem;
use Faker\Provider\en_GB\Internet;
use Faker\Provider\en_GB\Person;
use Faker\Provider\en_US\Text;

require_once __DIR__ . '/vendor/autoload.php';

Dotenv::createImmutable(__DIR__)->safeLoad();

$container = new DIContainer();

$faker = new Generator();

$faker->addProvider(new Lorem($faker));
$faker->addProvider(new Internet($faker));
$faker->addProvider(new Person($faker));
$faker->addProvider(new Text($faker));

$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/' . $_SERVER['SQLITE_DB_PATH'])
);

$container->bind(
    PasswordAuthenticationInterface::class,
    PasswordAuthentication::class
);

$container->bind(
    TokenAuthenticationInterface::class,
    TokenAuthentication::class
);

$container->bind(
    AuthTokensRepositoryInterface::class,
    SqliteAuthTokensRepository::class
);

$container->bind(
    UsersRepositoryInterface::class,
    SqliteUsersRepository::class
);

$container->bind(
    PostsRepositoryInterface::class,
    SqlitePostsRepository::class
);

$container->bind(
    CommentsRepositoryInterface::class,
    SqliteCommentsRepository::class
);

$container->bind(
    LikesRepositoryInterface::class,
    SqliteLikesRepository::class
);

$logger = (new Logger('blog'));

if ($_SERVER['LOG_TO_FILES'] === 'yes') {
    $logger
        ->pushHandler(new StreamHandler(
            __DIR__ . '/logs/blog.log'
        ))
        ->pushHandler(new StreamHandler(
            __DIR__ . '/logs/blog.error.log',
            level: Level::Error,
            bubble: false
        ));
}

if ($_SERVER['LOG_TO_CONSOLE'] === 'yes') {
    $logger->pushHandler(new StreamHandler("php://stdout"));
}

$container->bind(
    LoggerInterface::class,
    $logger
);

$container->bind(
    Generator::class,
    $faker
);

return $container;
