<?php

declare(strict_types=1);

namespace App\Application\Service\Game\League\Merge;

use App\Application\Service\Transliterator;
use App\Domain\Model\FlusherInterface;
use App\Domain\Model\Game\Entity\League;
use App\Domain\Model\Game\Entity\Sport;
use App\Domain\Model\Game\Entity\Uuid;
use App\Domain\Model\Game\Repository\LeagueRepository;
use App\Domain\Model\Game\Service\League\Merge;

class Handler implements Merge\Handler
{
    private $leagueRepository;
    private $flusher;
    private $transliterator;

    public function __construct(
        LeagueRepository $leagueRepository,
        Transliterator $transliterator,
        FlusherInterface $flusher
    ) {
        $this->leagueRepository = $leagueRepository;
        $this->transliterator = $transliterator;
        $this->flusher = $flusher;
    }

    public function handle(Merge\Command $command, Sport $sport): League
    {
        $transliteratedName = $this->transliterator->transliterate($command->name);
        $league = $this->leagueRepository->findByTransliteratedName($transliteratedName, $sport);

        if ($league) {
            return $league;
        }

        $league = new League(
            Uuid::generate(),
            $command->name,
            $transliteratedName,
            $sport
        );

        $this->leagueRepository->add($league);

        $this->flusher->flush();

        return $league;
    }
}
