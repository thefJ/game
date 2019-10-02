<?php

declare(strict_types=1);

namespace App\Domain\Model;

interface AggregateRoot
{
    public function releaseEvents(): array;
}
