<?php

use Alex\Weblog\Exceptions\HttpException;
use Alex\Weblog\http\Actions\Users\FindByUsername;
use Alex\Weblog\http\Actions\Posts\FindByUuid;
use Alex\Weblog\http\Actions\Likes\FindByPostUuid;
use Alex\Weblog\http\Actions\Users\CreateUser;
use Alex\Weblog\http\Actions\Posts\CreatePost;
use Alex\Weblog\http\Actions\Comments\CreateComment;
use Alex\Weblog\http\Actions\Likes\CreateLike;
use Alex\Weblog\http\Actions\Users\DeleteByUsername;
use Alex\Weblog\http\Actions\Posts\DeleteByUuid;
use Alex\Weblog\http\Request;
use Alex\Weblog\http\ErrorResponse;

$container = require __DIR__ . '/bootstrap.php';

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input')
);

try {
    $path = $request->path();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
        '/users/show' => FindByUsername::class,
        '/posts/show' => FindByUuid::class,
        '/likes/show' => FindByPostUuid::class,
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

if (!array_key_exists($method, $routes)) {
    (new ErrorResponse('Not found'))->send();
    return;
}

if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Not found'))->send();
    return;
}

$actionClassName = $routes[$method][$path];

$action = $container->get($actionClassName);

try {
    $response = $action->handle($request);
} catch (Exception $e) {
    (new ErrorResponse($e->getMessage()))->send();
}

$response->send();
