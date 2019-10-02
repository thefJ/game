<?php

declare(strict_types=1);

namespace App\Tests\Builder\Domain\Model\Game;

use App\Domain\Model\Game\Entity\League\League;
use App\Domain\Model\Game\Entity\Uuid;

/**
 * Class LeagueBuilder
 * @package App\Tests\Builder\Domain\Model\Game
 */
class LeagueBuilder
{
    private $name;

    public function __construct()
    {
        $this->name = 'Лига чемпионов';
    }

    public function withName(string $name): self
    {
        $clone = clone $this;
        $clone->name = $name;
        return $clone;
    }

    public function build(): League
    {
        return new League(
            Uuid::generate(),
            $this->name
        );
    }
}
