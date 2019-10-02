<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Service\League\Merge;

use App\Domain\Model\Game\Entity\League;
use App\Domain\Model\Game\Entity\Sport;

interface Handler
{
    public function handle(Command $command, Sport $sport): League;
}
