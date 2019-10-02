<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Model\Game\Entity\Sport;


use App\Domain\Model\Game\Entity\Sport\Sport;
use App\Domain\Model\Game\Entity\Uuid;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $sport = new Sport($uuid = Uuid::generate(), $name = 'Футбол');

        self::assertEquals($uuid, $sport->getId());
        self::assertEquals($name, $sport->getName());
    }
}
