<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Repository;

use App\Domain\Model\Game\Entity\League;
use App\Domain\Model\Game\Entity\Sport;
use App\Domain\Model\Game\Entity\Uuid;

interface LeagueRepository
{
    public function add(League $sport): void;
    public function getById(Uuid $uuid): League;
    public function findByTransliteratedName(string $transliteratedName, Sport $sport): ?League;
}
