<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Listener\Game;

use App\Domain\Model\Game\Entity\League;
use App\Domain\Model\Game\Entity\Sport;
use App\Domain\Model\Game\Entity\Team;
use App\Domain\Model\Game\Event\BufferGameCreated;
use App\Application\Service\Game\Sport\Merge\Handler as SportMergeHandler;
use App\Application\Service\Game\League\Merge\Handler as LeagueMergeHandler;
use App\Application\Service\Game\Team\Merge\Handler as TeamMergeHandler;
use App\Application\Service\Game\Game\Merge\Handler as GameMergeHandler;
use App\Domain\Model\Game\Service\BaseCommand;
use App\Infrastructure\Repository\Game\BufferGameRepository;
use App\Infrastructure\Service\Game\Sport\Merge\Command as SportMergeCommand;
use App\Infrastructure\Service\Game\League\Merge\Command as LeagueMergeCommand;
use App\Infrastructure\Service\Game\Team\Merge\Command as TeamMergeCommand;
use App\Presentation\Api\Service\ViolationFormatter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use DomainException;

class GameMergerSubscriber implements EventSubscriberInterface
{
    private $validator;
    private $sportMergeHandler;
    private $leagueMergeHandler;
    private $teamMergeHandler;
    private $gameMergeHandler;
    private $violationFormatter;
    private $bufferGameRepository;

    public function __construct(
        ValidatorInterface $validator,
        SportMergeHandler $sportMergeHandler,
        LeagueMergeHandler $leagueMergeHandler,
        TeamMergeHandler $teamMergeHandler,
        GameMergeHandler $gameMergeHandler,
        ViolationFormatter $violationFormatter,
        BufferGameRepository $bufferGameRepository
    ) {
        $this->validator = $validator;
        $this->sportMergeHandler = $sportMergeHandler;
        $this->leagueMergeHandler = $leagueMergeHandler;
        $this->teamMergeHandler = $teamMergeHandler;
        $this->gameMergeHandler = $gameMergeHandler;
        $this->violationFormatter = $violationFormatter;
        $this->bufferGameRepository = $bufferGameRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BufferGameCreated::class => [
                ['onBufferGameMerge'],
            ],
        ];
    }

    public function onBufferGameMerge(BufferGameCreated $event): void
    {
        $sport = $this->mergeSport($event->sport);
        $league = $this->mergeLeague($event->league, $sport);
        $hostTeam = $this->mergeTeam($event->hostTeam, $sport);
        $guestTeam = $this->mergeTeam($event->guestTeam, $sport);
        $language = $event->language;
        $source = $event->source;
        $bufferGame = $this->bufferGameRepository->getByHash($event->hash);
        $this->gameMergeHandler->handle(
            $sport,
            $league,
            $hostTeam,
            $guestTeam,
            $event->date,
            $language,
            $source,
            $bufferGame
        );
    }

    private function mergeSport(string $sportName): Sport
    {
        $sportMergeCommand = new SportMergeCommand($sportName);
        $this->validate($sportMergeCommand);

        return $this->sportMergeHandler->handle($sportMergeCommand);
    }

    private function mergeLeague(string $leagueName, Sport $sport): League
    {
        $leagueMergeCommand = new LeagueMergeCommand($leagueName);
        $this->validate($leagueMergeCommand);

        return $this->leagueMergeHandler->handle($leagueMergeCommand, $sport);
    }

    private function mergeTeam(string $teamName, Sport $sport): Team
    {
        $teamMergeCommand = new TeamMergeCommand($teamName);
        $this->validate($teamMergeCommand);

        return $this->teamMergeHandler->handle($teamMergeCommand, $sport);
    }

    private function validate(BaseCommand $command)
    {
        $violationList = $this->validator->validate($command);
        if ($violationList->count()) {
            $violations = $this->violationFormatter->format($violationList);
            throw new DomainException('Errors: '.serialize($violations));
        }
    }
}
