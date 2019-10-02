<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Service\Game\Merge;

use App\Domain\Model\Game\Entity\BufferGame;
use App\Domain\Model\Game\Entity\League;
use App\Domain\Model\Game\Entity\Sport;
use App\Domain\Model\Game\Entity\Team;

interface Handler
{
    public function handle(
        Sport $sport,
        League $league,
        Team $hostTeam,
        Team $guestTeam,
        \DateTimeImmutable $date,
        string $language,
        string $source,
        BufferGame $bufferGame
    ): void;
}
