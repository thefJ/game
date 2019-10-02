<?php

declare(strict_types=1);

namespace App\Application\Service\Game\Team\Merge;

use App\Application\Service\Transliterator;
use App\Domain\Model\FlusherInterface;
use App\Domain\Model\Game\Entity\Sport;
use App\Domain\Model\Game\Entity\Team;
use App\Domain\Model\Game\Entity\Uuid;
use App\Domain\Model\Game\Repository\TeamRepository;
use App\Domain\Model\Game\Service\Team\Merge;

class Handler implements Merge\Handler
{
    private $teamRepository;
    private $flusher;
    private $transliterator;

    public function __construct(
        TeamRepository $teamRepository,
        Transliterator $transliterator,
        FlusherInterface $flusher
    ) {
        $this->teamRepository = $teamRepository;
        $this->transliterator = $transliterator;
        $this->flusher = $flusher;
    }

    public function handle(Merge\Command $command, Sport $sport): Team
    {
        $transliteratedName = $this->transliterator->transliterate($command->name);
        $team = $this->teamRepository->findByTransliteratedName($transliteratedName, $sport);

        if ($team) {
           return $team;
        }

        $team = new Team(
            Uuid::generate(),
            $command->name,
            $transliteratedName,
            $sport
        );

        $this->teamRepository->add($team);

        $this->flusher->flush();

        return $team;
    }
}
