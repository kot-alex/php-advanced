<?php

use Alex\Weblog\Exceptions\HttpException;
use Alex\Weblog\http\Actions\Users\FindByUsername;
use Alex\Weblog\http\Actions\Posts\FindPostByUuid;
use Alex\Weblog\http\Actions\Comments\FindCommentByUuid;
use Alex\Weblog\http\Actions\Likes\FindByPostUuid;
use Alex\Weblog\http\Actions\Users\CreateUser;
use Alex\Weblog\http\Actions\Posts\CreatePost;
use Alex\Weblog\http\Actions\Comments\CreateComment;
use Alex\Weblog\http\Actions\Likes\CreateLike;
use Alex\Weblog\http\Actions\Users\DeleteByUsername;
use Alex\Weblog\http\Actions\Posts\DeleteByUuid;
use Alex\Weblog\http\Request;
use Alex\Weblog\http\ErrorResponse;
use Psr\Log\LoggerInterface;

$container = require __DIR__ . '/bootstrap.php';

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input')
);

$logger = $container->get(LoggerInterface::class);

try {
    $path = $request->path();
} catch (HttpException) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
        '/users/show' => FindByUsername::class,
        '/posts/show' => FindPostByUuid::class,
        '/comments/show' => FindCommentByUuid::class,
        '/likes/showbypost' => FindByPostUuid::class,
    ],
    'POST' => [
        '/users/create' => CreateUser::class,
        '/posts/create' => CreatePost::class,
        '/posts/comment' => CreateComment::class,
        '/posts/like' => CreateLike::class,
    ],
    'DELETE' => [
        '/users/delete' => DeleteByUsername::class,
        '/posts/delete' => DeleteByUuid::class,
    ],
];

if (!array_key_exists($method, $routes) || !array_key_exists($path, $routes[$method])) {
    $message = "Route not found: $method $path";
    $logger->notice($message);
    (new ErrorResponse('Not found'))->send();
    return;
}

$actionClassName = $routes[$method][$path];

try {
    $action = $container->get($actionClassName);
    $response = $action->handle($request);
} catch (Exception $e) {
    $logger->error($e->getMessage(), ['exception' => $e]);
    (new ErrorResponse)->send();
}

$response->send();
