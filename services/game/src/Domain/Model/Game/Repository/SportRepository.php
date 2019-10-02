<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Repository;

use App\Domain\Model\Game\Entity\Sport;
use App\Domain\Model\Game\Entity\Uuid;

interface SportRepository
{
    public function add(Sport $sport);
    public function getById(Uuid $uuid): Sport;
    public function findByTransliteratedName(string $transliteratedName): ?Sport;
}
