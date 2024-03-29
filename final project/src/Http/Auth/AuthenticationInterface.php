<?php
namespace GeekBrains\Http\Auth;


use GeekBrains\Http\Request;
use GeekBrains\Person\User;

interface AuthenticationInterface
{
// Контракт описывает единственный метод,
// получающий пользователя из запроса
    public function user(Request $request): User;
}
