<?php

declare(strict_types=1);

namespace App\Application\Service\Game\BufferGame\Create;

use App\Domain\Model\FlusherInterface;
use App\Domain\Model\Game\Entity\BufferGame;
use App\Domain\Model\Game\Repository\BufferGameRepository;
use App\Domain\Model\Game\Service\BufferGame\Create;
use DomainException;

class Handler implements Create\Handler
{
    private $bufferGameRepository;
    private $flusher;

    public function __construct(BufferGameRepository $bufferGameRepository, FlusherInterface $flusher)
    {
        $this->bufferGameRepository = $bufferGameRepository;
        $this->flusher = $flusher;
    }

    public function handle(Create\Command $command): void
    {

        $bufferGame = new BufferGame(
            $command->sport,
            $command->league,
            $command->hostTeam,
            $command->guestTeam,
            $command->date,
            $command->language,
            $command->source
        );

        if ($this->bufferGameRepository->findByHash($bufferGame->getHash())) {
            throw new DomainException('Game already exists.');
        }

        $this->bufferGameRepository->add($bufferGame);

        $this->flusher->flush($bufferGame);
    }
}
