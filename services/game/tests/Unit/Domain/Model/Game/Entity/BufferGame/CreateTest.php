<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Model\Game\Entity\BufferGame;


use App\Domain\Model\Game\Entity\BufferGame\BufferGame;
use App\Domain\Model\Game\Entity\Language;
use App\Domain\Model\Game\Entity\Source;
use App\Domain\Model\Game\Entity\Uuid;
use App\Tests\Builder\Domain\Model\Game\LeagueBuilder;
use App\Tests\Builder\Domain\Model\Game\SportBuilder;
use App\Tests\Builder\Domain\Model\Game\TeamBuilder;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $game = new BufferGame(
            $uuid = Uuid::generate(),
            $sport = (new SportBuilder())->build(),
            $league = (new LeagueBuilder())->build(),
            $hostTeam = (new TeamBuilder())->build(),
            $guestTeam = (new TeamBuilder())->withName('Реал Мадрид')->build(),
            $date = new \DateTimeImmutable(),
            $language = new Language('русский'),
            $source = new Source('sportdata.com')
        );

        self::assertEquals($uuid, $game->getId());
        self::assertEquals($sport, $game->getSport());
        self::assertEquals($league, $game->getLeague());
        self::assertEquals($hostTeam, $game->getHostTeam());
        self::assertEquals($guestTeam, $game->getGuestTeam());
        self::assertEquals($date, $game->getDate());
        self::assertEquals($language, $game->getLanguage());
        self::assertEquals($source, $game->getSource());
    }
}
