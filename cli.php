<?php

use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Person\{Name, Person};
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Comment;

include __DIR__ . "/vendor/autoload.php";

$faker = Faker\Factory::create('ru_RU');

$name = new Name($faker->name());
$login = $faker->email();
$text = $faker->text();

$user = new User(1, $name, $login);

$person = new Person($name, new DateTimeImmutable());

$post = new Post(
    1,
    $person,
    'Всем привет!',
    'Hello!'
);

$comment = new Comment($person, $post, $text);

echo $user;
echo $post;
echo $comment;
