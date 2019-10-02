<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Game;

use App\Domain\Model\Game\Entity\Sport;
use App\Domain\Model\Game\Entity\Uuid;
use App\Domain\Model\Game\Repository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;

class SportRepository implements Repository\SportRepository
{
    private $entityManager;
    private $repository;
    private $connection;

    public function __construct(Connection $connection, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Sport::class);
        $this->connection = $connection;
    }

    public function add(Sport $sport): void
    {
        $this->entityManager->persist($sport);
    }

    public function getById(Uuid $uuid): Sport
    {
        $sport = $this->repository->find($uuid);
        if (!$sport) {
            throw new \DomainException('Sport is not found.');
        }

        return $sport;
    }

    public function findByTransliteratedName(string $transliteratedName): ?Sport
    {
        $sportId = $this->connection->createQueryBuilder()
            ->select('id')
            ->from('sport')
            ->andWhere('similarity(transliterated_name, :transliterated_name) > 0.05')
            ->setParameter(':transliterated_name', $transliteratedName)
            ->orderBy('transliterated_name <-> :transliterated_name', 'DESC')
            ->setMaxResults(1)
            ->execute()
            ->fetchColumn();

        return $sportId ? $this->getById(new Uuid($sportId)) : null;
    }
}
