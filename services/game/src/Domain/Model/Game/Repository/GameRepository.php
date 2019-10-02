<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Repository;


use App\Domain\Model\Game\Entity\Game;
use App\Domain\Model\Game\Entity\League;
use App\Domain\Model\Game\Entity\Sport;
use App\Domain\Model\Game\Entity\Team;
use App\Domain\Model\Game\Entity\Uuid;

interface GameRepository
{
    public function add(Game $game): void;

    public function getById(Uuid $uuid): Game;

    public function getRandom(array $filter = []): Game;

    public function findByBuffer(
        Sport $sport,
        League $league,
        Team $hostTeam,
        Team $guestTeam,
        \DateTimeImmutable $dateFrom,
        \DateTimeImmutable $dateTo
    ): ?Game;
}
