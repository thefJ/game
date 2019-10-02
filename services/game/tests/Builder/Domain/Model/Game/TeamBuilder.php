<?php

declare(strict_types=1);

namespace App\Tests\Builder\Domain\Model\Game;

use App\Domain\Model\Game\Entity\Team\Team;
use App\Domain\Model\Game\Entity\Uuid;

/**
 * Class TeamBuilder
 * @package App\Tests\Builder\Domain\Model\Game
 */
class TeamBuilder
{
    private $name;

    public function __construct()
    {
        $this->name = 'Барселона';
    }

    public function withName(string $name): self
    {
        $clone = clone $this;
        $clone->name = $name;
        return $clone;
    }

    public function build(): Team
    {
        return new Team(
            Uuid::generate(),
            $this->name
        );
    }
}
