<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Repository;

use App\Domain\Model\Game\Entity\BufferGame;

interface BufferGameRepository
{
    public function add(BufferGame $bufferGame);
}
