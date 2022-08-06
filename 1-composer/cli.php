<?php

require_once __DIR__ . '/vendor/autoload.php';

use Alex\Weblog\User;
use Alex\Weblog\Article;
use Alex\Weblog\Comment;

$faker = Faker\Factory::create();

if (isset($argv[1])) {
    switch ($argv[1]) {
        case 'user':
            $user = new User(hexdec(uniqid()), $faker->firstname(), $faker->lastname());
            print($user);
            break;
        case 'article':
            $article = new Article(hexdec(uniqid()), $faker->randomNumber(), $faker->text(30), $faker->text());
            print($article);
            break;
        case 'comment':
            $comment = new Comment(hexdec(uniqid()), $faker->randomNumber(), $faker->randomNumber(), $faker->text());
            print($comment);
            break;
        default:
            print('no such argument');
    }
}
