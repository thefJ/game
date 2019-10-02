<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Entity;

use App\Domain\Model\AggregateRoot;
use App\Domain\Model\EventsTrait;
use App\Domain\Model\Game\Event\BufferGameCreated;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity
 * @ORM\Table(name="game_buffer")
 */
class BufferGame implements AggregateRoot
{
    use EventsTrait;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $hash;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $sport;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $league;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $hostTeam;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $guestTeam;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $language;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $source;

    /**
     * @var Game
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Game\Entity\Game", inversedBy="bufferGames")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id", nullable=true),
     */
    private $game;

    public function __construct(
        string $sport,
        string $league,
        string $hostTeam,
        string $guestTeam,
        DateTimeImmutable $date,
        string $language,
        string $source
    ) {
        $this->sport = $sport;
        $this->league = $league;
        $this->hostTeam = $hostTeam;
        $this->guestTeam = $guestTeam;
        $this->date = $date;
        $this->language = $language;
        $this->source = $source;

        $this->generateHash();

        $this->recordEvent(new BufferGameCreated($this));
    }

    public function isHashEqual(string $hash): bool
    {
        return $this->hash === $hash;
    }

    /**
     * @return void
     */
    public function generateHash(): void
    {
        $this->hash = md5(
            $this->sport.$this->league.$this->hostTeam.$this->guestTeam.$this->date->format('Y-m-d H:i:s').$this->source
        );
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param Game $game
     * @return
     */
    public function setGame(Game $game): void
    {
        $this->game = $game;
    }

    /**
     * @return string
     */
    public function getSport(): string
    {
        return $this->sport;
    }

    /**
     * @return string
     */
    public function getLeague(): string
    {
        return $this->league;
    }

    /**
     * @return string
     */
    public function getHostTeam(): string
    {
        return $this->hostTeam;
    }

    /**
     * @return string
     */
    public function getGuestTeam(): string
    {
        return $this->guestTeam;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return Game
     */
    public function getGame(): ?Game
    {
        return $this->game;
    }
}
