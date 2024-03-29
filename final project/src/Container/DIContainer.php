<?php

namespace GeekBrains\Container;
use Psr\Container\ContainerInterface;
use ReflectionClass;

class DIContainer implements ContainerInterface
{
    private array $resolvers = [];

    // Метод has из PSR-11
    public function has(string $type): bool
    {
// Здесь мы просто пытаемся создать
// объект требуемого типа
        try {
            $this->get($type);
        } catch (NotFoundException $e) {
// Возвращаем false, если объект не создан...
            return false;
        }
// и true, если создан
        return true;
    }

    public function bind(string $type, $resolver)
    {
        $this->resolvers[$type] = $resolver;
    }
    public function get(string $type): object
    {

        if (array_key_exists($type, $this->resolvers)) {

            $typeToCreate = $this->resolvers[$type];

            if (is_object($typeToCreate)) {
                return $typeToCreate;
            }

            return $this->get($typeToCreate);
        }
        if (!class_exists($type)) {
            throw new NotFoundException("Cannot resolve type: $type");
        }
        // Создаём объект рефлексии для запрашиваемого класса
        $reflectionClass = new ReflectionClass($type);
// Исследуем конструктор класса
        $constructor = $reflectionClass->getConstructor();
// Если конструктора нет -
// просто создаём объект нужного класса
        if (null === $constructor) {
            return new $type();
        }
// В этот массив мы будем собирать
// объекты зависимостей класса
        $parameters = [];
// Проходим по всем параметрам конструктора
// (зависимостям класса)
        foreach ($constructor->getParameters() as $parameter) {
// Узнаем тип параметра конструктора
// (тип зависимости)
            $parameterType = $parameter->getType()->getName();
// Получаем объект зависимости из контейнера
            $parameters[] = $this->get($parameterType);
        }
// Создаём объект нужного нам типа
// с параметрами
        return new $type(...$parameters);
    }

}