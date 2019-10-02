<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Model\AggregateRoot;
use App\Domain\Model\EventDispatcher;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Model\FlusherInterface;

class Flusher implements FlusherInterface
{
    private $entityManager;
    private $dispatcher;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcher $dispatcher)
    {
        $this->entityManager = $entityManager;
        $this->dispatcher = $dispatcher;
    }

    public function flush(AggregateRoot ...$roots): void
    {
        $this->entityManager->flush();

        foreach ($roots as $root) {
            $this->dispatcher->dispatch($root->releaseEvents());
        }
    }
}
