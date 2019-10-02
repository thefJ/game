<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Repository;

use App\Domain\Model\Game\Entity\Sport;
use App\Domain\Model\Game\Entity\Team;
use App\Domain\Model\Game\Entity\Uuid;

interface TeamRepository
{
    public function add(Team $sport): void;
    public function merge(Team $sport): void;
    public function getById(Uuid $uuid): Team;
    public function findByTransliteratedName(string $transliteratedName, Sport $sport): ?Team;
}
