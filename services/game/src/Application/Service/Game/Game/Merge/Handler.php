<?php

declare(strict_types=1);

namespace App\Application\Service\Game\Game\Merge;

use App\Domain\Model\FlusherInterface;
use App\Domain\Model\Game\Entity\BufferGame;
use App\Domain\Model\Game\Entity\Game;
use App\Domain\Model\Game\Entity\League;
use App\Domain\Model\Game\Entity\Sport;
use App\Domain\Model\Game\Entity\Team;
use App\Domain\Model\Game\Entity\Uuid;
use App\Domain\Model\Game\Repository\GameRepository;
use App\Domain\Model\Game\Service\Game\Merge;

class Handler implements Merge\Handler
{
    private $gameRepository;
    private $flusher;

    public function __construct(
        GameRepository $gameRepository,
        FlusherInterface $flusher
    ) {
        $this->gameRepository = $gameRepository;
        $this->flusher = $flusher;
    }

    public function handle(
        Sport $sport,
        League $league,
        Team $hostTeam,
        Team $guestTeam,
        \DateTimeImmutable $date,
        string $language,
        string $source,
        BufferGame $bufferGame
    ): void {
        $dateFrom = $date->sub(new \DateInterval(Game::TIME_INTERVAL));
        $dateTo = $date->add(new \DateInterval(Game::TIME_INTERVAL));
        $game = $this->gameRepository->findByBuffer($sport, $league, $hostTeam, $guestTeam, $dateFrom, $dateTo);

        if (!$game) {
            $game = new Game(Uuid::generate(), $sport, $league, $hostTeam, $guestTeam, $date, $language, $source);
            $this->gameRepository->add($game);

        }
        $game->addBufferGame($bufferGame);
        $this->mergeExistsDate($game);
        $this->flusher->flush();
    }

    private function mergeExistsDate(Game $game): void
    {
        $bufferGames = $game->getBufferGames();
        $dates = array();
        foreach ($bufferGames as $bufferGame) {
            $dateTimestamp = $bufferGame->getDate()->getTimestamp();
            $dates[$dateTimestamp] = isset($dates[$dateTimestamp]) ? $dates[$dateTimestamp]++ : $dates[$dateTimestamp] = 1;
        }
        arsort($dates);
        $date = array_key_first($dates);
        $game->setDate((new \DateTimeImmutable())->setTimestamp($date));
    }
}
