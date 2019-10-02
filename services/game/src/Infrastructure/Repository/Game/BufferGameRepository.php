<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Game;

use App\Domain\Model\Game\Entity\BufferGame;
use App\Domain\Model\Game\Repository;
use Doctrine\ORM\EntityManagerInterface;

class BufferGameRepository implements Repository\BufferGameRepository
{

    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(BufferGame::class);
    }

    public function add(BufferGame $bufferGame): void
    {
        $this->entityManager->persist($bufferGame);
    }

    public function getByHash(string $hash): BufferGame
    {
        $bufferGame = $this->repository->findOneBy(['hash' => $hash]);
        if (!$bufferGame) {
            throw new \DomainException('Buffer game is not found.');
        }

        return $bufferGame;
    }

    public function findByHash(string $hash): ?BufferGame
    {
        return $this->repository->findOneBy(['hash' => $hash]);
    }
}
