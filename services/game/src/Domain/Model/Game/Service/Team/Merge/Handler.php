<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Service\Team\Merge;

use App\Domain\Model\Game\Entity\Sport;
use App\Domain\Model\Game\Entity\Team;

interface Handler
{
    public function handle(Command $command, Sport $sport): Team;
}
