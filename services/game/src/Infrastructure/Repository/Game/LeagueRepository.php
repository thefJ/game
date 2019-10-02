<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Game;

use App\Domain\Model\Game\Entity\League;
use App\Domain\Model\Game\Entity\Sport;
use App\Domain\Model\Game\Entity\Uuid;
use App\Domain\Model\Game\Repository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;

class LeagueRepository implements Repository\LeagueRepository
{
    private $entityManager;
    private $repository;
    private $connection;

    public function __construct(Connection $connection, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(League::class);
        $this->connection = $connection;
    }

    public function add(League $league): void
    {
        $this->entityManager->persist($league);
    }

    public function getById(Uuid $uuid): League
    {
        $league = $this->repository->findOneBy(['id' => $uuid]);
        if (!$league) {
            throw new \DomainException('League is not found.');
        }

        return $league;
    }

    public function findByTransliteratedName(string $transliteratedName, Sport $sport): ?League
    {
        $leagueId = $this->connection->createQueryBuilder()
            ->select('id')
            ->from('league')
            ->andWhere('similarity(transliterated_name, :transliterated_name) > 0.05')
            ->andWhere('sport_id = :sport_id')
            ->setParameter(':transliterated_name', $transliteratedName)
            ->setParameter(':sport_id', $sport->getId())
            ->orderBy('transliterated_name <-> :transliterated_name', 'DESC')
            ->setMaxResults(1)
            ->execute()
            ->fetchColumn();

        return $leagueId ? $this->getById(new Uuid($leagueId)) : null;
    }
}
