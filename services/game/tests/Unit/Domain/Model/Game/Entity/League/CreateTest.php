<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Model\Game\Entity\League;


use App\Domain\Model\Game\Entity\League\League;
use App\Domain\Model\Game\Entity\Uuid;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $sport = new League($uuid = Uuid::generate(), $name = 'Лига чемпионов');

        self::assertEquals($uuid, $sport->getId());
        self::assertEquals($name, $sport->getName());
    }
}
