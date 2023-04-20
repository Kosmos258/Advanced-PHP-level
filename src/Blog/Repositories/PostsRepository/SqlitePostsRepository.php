<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;
use \PDO;
use \PDOStatement;

class SqliteUsersRepository implements PostsRepositoryInterface
{
  private PDO $connection;

  public function __construct(PDO $connection)
  {
    $this->connection = $connection;
  }


  public function save(Post $post): void
  {

    // Подготавливаем запрос
    $statement = $this->connection->prepare(
      'INSERT INTO posts (uuid, author_uuid, title, text) 
VALUES (:uuid, :author_uuid, :title, :text)'

    );
    // Выполняем запрос с конкретными значениями
    $statement->execute([
      ':uuid' => (string)$post->uuid(),
      ':last_name' => $post->name()->last(),
      ':username' => $post->username(),
    ]);
  }

  // Также добавим метод для получения
  // пользователя по его UUID
  /**
   * @throws UserNotFoundException
   * @throws InvalidArgumentException
   */
  public function get(UUID $uuid): Post
  {
    $statement = $this->connection->prepare(
      'SELECT * FROM users WHERE uuid = ?'
    );

    $statement->execute([(string)$uuid]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    // Бросаем исключение, если пользователь не найден
    if ($result === false) {
      throw new UserNotFoundException(
        "Cannot get user: $uuid"
      );
    }
    return $this->getUser($statement, $uuid);
  }

  /**
   * @throws UserNotFoundException
   * @throws InvalidArgumentException
   */
  public function getByUsername(string $username): Post
  {
    $statement = $this->connection->prepare(
      'SELECT * FROM users WHERE username = :username'
    );
    $statement->execute([
      ':username' => $username,
    ]);

    return $this->getUser($statement, $username);
  }

  /**
   * @throws UserNotFoundException
   * @throws InvalidArgumentException
   */
  private function getUser(PDOStatement $statement, string $errorString): Post
  {
    $result = $statement->fetch(\PDO::FETCH_ASSOC);
    if ($result === false) {
      throw new UserNotFoundException(
        "Cannot find post: $errorString"
      );
    }
    // Создаём объект пользователя с полем username
    return new Post(
      new UUID($result['uuid']),
      new Post($result['first_name'], $result['last_name']),
      $result['username'],
    );
  }
}
