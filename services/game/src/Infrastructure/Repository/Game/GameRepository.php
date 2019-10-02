<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Game;

use App\Domain\Model\Game\Entity\Game;
use App\Domain\Model\Game\Entity\League;
use App\Domain\Model\Game\Entity\Sport;
use App\Domain\Model\Game\Entity\Team;
use App\Domain\Model\Game\Entity\Uuid;
use App\Domain\Model\Game\Repository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class GameRepository implements Repository\GameRepository
{
    private const TABLE_NAME = 'game';
    private $connection;
    private $entityManager;
    private $repository;

    public function __construct(Connection $connection, EntityManagerInterface $entityManager)
    {
        $this->connection = $connection;
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Game::class);
    }

    public function add(Game $game): void
    {
        $this->entityManager->persist($game);
    }

    public function getById(Uuid $uuid): Game
    {
        $game = $this->repository->find($uuid);
        if (!$game) {
            throw new \DomainException('Game is not found.');
        }

        return $game;
    }

    public function getRandom(array $filters = []): Game
    {
        $query = $this->connection->createQueryBuilder()
            ->from(self::TABLE_NAME)
            ->setMaxResults(1);

        if (isset($filters['source'])) {
            $query->andWhere('source = :source');
            $query->setParameter(':source', $filters['source']);
        }

        if (isset($filters['from'])) {
            $query->andWhere('date >= :from');
            $query->setParameter(':from', $filters['from']);
        }

        if (isset($filters['to'])) {
            $query->andWhere('date <= :to');
            $query->setParameter(':to', $filters['to']);
        }

        $count = $query->select('COUNT(id)')->execute()->fetchColumn();
        if (!$count) {
            throw new \DomainException('Game is not found.');
        }

        $offset = max(0, rand(0, $count - 1));
        $gameId = $query->select('id')->setFirstResult((int)$offset)->execute()->fetchColumn();

        return $this->getById(new Uuid($gameId));
    }

    public function findByBuffer(
        Sport $sport,
        League $league,
        Team $hostTeam,
        Team $guestTeam,
        \DateTimeImmutable $dateFrom,
        \DateTimeImmutable $dateTo
    ): ?Game {
        $gameId = $this->connection->createQueryBuilder()
            ->select('id')
            ->from(self::TABLE_NAME)
            ->andWhere('sport_id = :sport')
            ->andWhere('league_id = :league')
            ->andWhere('host_team_id = :hostTeam')
            ->andWhere('guest_team_id = :guestTeam')
            ->andWhere('date >= :dateFrom')
            ->andWhere('date <= :dateTo')
            ->setParameter(':sport', $sport->getId())
            ->setParameter(':league', $league->getId())
            ->setParameter(':hostTeam', $hostTeam->getId())
            ->setParameter(':guestTeam', $guestTeam->getId())
            ->setParameter(':dateFrom', $dateFrom->format('Y-m-d H:i:s'))
            ->setParameter(':dateTo', $dateTo->format('Y-m-d H:i:s'))
            ->setMaxResults(1)
            ->execute()
            ->fetchColumn();

        return $gameId ? $this->getById(new Uuid($gameId)) : null;
    }
}
