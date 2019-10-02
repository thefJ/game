<?php

declare(strict_types=1);

namespace App\Domain\Model;

interface EventDispatcher
{
    public function dispatch(array $events): void;
}
