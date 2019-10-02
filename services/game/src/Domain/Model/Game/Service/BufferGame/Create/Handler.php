<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Service\BufferGame\Create;

interface Handler
{
    public function handle(Command $command): void;
}
