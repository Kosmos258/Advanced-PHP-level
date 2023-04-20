<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepository;

use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;

interface PostsRepositoryInterface
{
  public function save(Post $user): void;
  public function get(UUID $uuid): Post;
  public function getByUsername(string $username): Post;
}
