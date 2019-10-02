<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Model\Game\Entity\Team;


use App\Domain\Model\Game\Entity\Team\Team;
use App\Domain\Model\Game\Entity\Uuid;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $sport = new Team($uuid = Uuid::generate(), $name = 'Барселона');

        self::assertEquals($uuid, $sport->getId());
        self::assertEquals($name, $sport->getName());
    }
}
