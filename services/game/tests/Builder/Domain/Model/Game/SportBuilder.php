<?php

declare(strict_types=1);

namespace App\Tests\Builder\Domain\Model\Game;

use App\Domain\Model\Game\Entity\Sport\Sport;
use App\Domain\Model\Game\Entity\Uuid;

/**
 * Class SportBuilder
 * @package App\Tests\Builder\Domain\Model\Game
 */
class SportBuilder
{
    private $name;

    public function __construct()
    {
        $this->name = 'Футбол';
    }

    public function withName(string $name): self
    {
        $clone = clone $this;
        $clone->name = $name;
        return $clone;
    }

    public function build(): Sport
    {
        return new Sport(
            Uuid::generate(),
            $this->name
        );
    }
}
