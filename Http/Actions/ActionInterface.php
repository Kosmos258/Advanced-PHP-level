<?php

namespace GeekBrains\LevelTwo\Http\ActionInterface;

use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\http\Response;

interface ActionInterface
{
    public function handle(Request $request): Response;
    public function checkUserLikeForPostExists($postUuid, $userUuid): void;
}
