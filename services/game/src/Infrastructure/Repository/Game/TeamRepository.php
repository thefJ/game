<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Game;

use App\Domain\Model\Game\Entity\Sport;
use App\Domain\Model\Game\Entity\Team;
use App\Domain\Model\Game\Entity\Uuid;
use App\Domain\Model\Game\Repository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;

class TeamRepository implements Repository\TeamRepository
{
    private $entityManager;
    private $repository;
    private $connection;

    public function __construct(Connection $connection, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Team::class);
        $this->connection = $connection;
    }

    public function add(Team $team): void
    {
        $this->entityManager->persist($team);
    }

    public function merge(Team $team): void
    {
        $this->entityManager->merge($team);
    }

    public function getById(Uuid $uuid): Team
    {
        return $this->repository->findOneBy(['id' => $uuid]);
    }

    public function findByTransliteratedName(string $transliteratedName, Sport $sport): ?Team
    {
        $teamId = $this->connection->createQueryBuilder()
            ->select('id')
            ->from('team')
            ->andWhere('similarity(transliterated_name, :transliterated_name) > 0.05')
            ->andWhere('sport_id = :sport_id')
            ->setParameter(':transliterated_name', $transliteratedName)
            ->setParameter(':sport_id', $sport->getId())
            ->orderBy('transliterated_name <-> :transliterated_name', 'DESC')
            ->setMaxResults(1)
            ->execute()
            ->fetchColumn();

        return $teamId ? $this->getById(new Uuid($teamId)) : null;
    }
}
