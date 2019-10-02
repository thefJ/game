<?php

declare(strict_types=1);

namespace App\Application\Service\Game\Sport\Merge;

use App\Application\Service\Transliterator;
use App\Domain\Model\FlusherInterface;
use App\Domain\Model\Game\Entity\Sport;
use App\Domain\Model\Game\Entity\Uuid;
use App\Domain\Model\Game\Repository\SportRepository;
use App\Domain\Model\Game\Service\Sport\Merge;

class Handler implements Merge\Handler
{
    private $sportRepository;
    private $flusher;
    private $transliterator;

    public function __construct(
        SportRepository $sportRepository,
        Transliterator $transliterator,
        FlusherInterface $flusher
    ) {
        $this->sportRepository = $sportRepository;
        $this->transliterator = $transliterator;
        $this->flusher = $flusher;
    }

    public function handle(Merge\Command $command): Sport
    {
        $transliteratedName = $this->transliterator->transliterate($command->name);
        $sport = $this->sportRepository->findByTransliteratedName($transliteratedName);

        if ($sport) {
            return $sport;
        }

        $sport = new Sport(
            Uuid::generate(),
            $command->name,
            $transliteratedName
        );

        $this->sportRepository->add($sport);

        $this->flusher->flush();

        return $sport;
    }
}
