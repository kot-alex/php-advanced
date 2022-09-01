<?php

use Alex\Weblog\Blog\Commands\Users\CreateUser;
use Alex\Weblog\Blog\Commands\Users\UpdateUser;
use Alex\Weblog\Blog\Commands\Posts\DeletePost;
use Alex\Weblog\Blog\Commands\FakeData\PopulateDB;
use Symfony\Component\Console\Application;

$container = require __DIR__ . '/bootstrap.php';

$application = new Application();

$commandsClasses = [
    CreateUser::class,
    DeletePost::class,
    UpdateUser::class,
    PopulateDB::class,
];

foreach ($commandsClasses as $commandClass) {
    $command = $container->get($commandClass);
    $application->add($command);
}

$application->run();

// php cli.php users:create Ivan Ivanov user3 pass3

// php cli.php posts:delete 8a0197b8-0504-4aa8-bf41-b3f16088bd9a

// php cli.php posts:delete 8a0197b8-0504-4aa8-bf41-b3f16088bd9a --check-existence

// php cli.php users:update 82b07161-a6cf-4e78-a8e2-01d73d137432 -f Pavel -l Pavlov

// php cli.php fake-data:populate-db

// php cli.php fake-data:populate-db -u 5 -p 3