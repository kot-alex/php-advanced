<?php

use Alex\Weblog\Blog\Commands\CreateUserCommand;
use Alex\Weblog\Blog\Commands\Arguments;
use Psr\Log\LoggerInterface;

$container = require __DIR__ . '/bootstrap.php';

$logger = $container->get(LoggerInterface::class);

try {
    $command = $container->get(CreateUserCommand::class);
    $command->handle(Arguments::fromArgv($argv));
} catch (Exception $exception) {
    $logger->error($exception->getMessage(), ['exception' => $exception]);
    echo $exception->getMessage();
}

// php cli.php username=user2 first_name=Petr last_name=Petrov