<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Entity;

use App\Domain\Model\AggregateRoot;
use App\Domain\Model\EventsTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity
 * @ORM\Table(name="game")
 */
class Game implements AggregateRoot
{
    use EventsTrait;

    public const TIME_INTERVAL = 'PT26H';

    /**
     * @var Uuid
     * @ORM\Column(type="uuid")
     * @ORM\Id
     */
    private $id;

    /**
     * @var Sport
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Game\Entity\Sport")
     * @ORM\JoinColumn(name="sport_id", referencedColumnName="id", nullable=false)
     */
    private $sport;

    /**
     * @var League
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Game\Entity\League")
     * @ORM\JoinColumn(name="league_id", referencedColumnName="id", nullable=false)
     */
    private $league;

    /**
     * @var Team
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Game\Entity\Team")
     * @ORM\JoinColumn(name="host_team_id", referencedColumnName="id", nullable=false)
     */
    private $hostTeam;

    /**
     * @var Team
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Game\Entity\Team")
     * @ORM\JoinColumn(name="guest_team_id", referencedColumnName="id", nullable=false)
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
     * @var ArrayCollection|BufferGame[]
     * @ORM\OneToMany(
     *     targetEntity="App\Domain\Model\Game\Entity\BufferGame",
     *     mappedBy="game",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     */
    private $bufferGames;

    public function __construct(
        Uuid $id,
        Sport $sport,
        League $league,
        Team $hostTeam,
        Team $guestTeam,
        DateTimeImmutable $date,
        string $language,
        string $source
    ) {
        $this->id = $id;
        $this->sport = $sport;
        $this->league = $league;
        $this->hostTeam = $hostTeam;
        $this->guestTeam = $guestTeam;
        $this->date = $date;
        $this->language = $language;
        $this->source = $source;
        $this->bufferGames = new ArrayCollection();
    }

    public function setDate(\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function addBufferGame(BufferGame $bufferGame): void
    {
        if ($this->bufferGames->contains($bufferGame)) {
            throw new \DomainException('Buffer game already exists.');
        }
        $bufferGame->setGame($this);
        $this->bufferGames->add($bufferGame);
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return Sport
     */
    public function getSport(): Sport
    {
        return $this->sport;
    }

    /**
     * @return League
     */
    public function getLeague(): League
    {
        return $this->league;
    }

    /**
     * @return Team
     */
    public function getHostTeam(): Team
    {
        return $this->hostTeam;
    }

    /**
     * @return Team
     */
    public function getGuestTeam(): Team
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
     * @return array|BufferGame[]
     */
    public function getBufferGames(): array
    {
        return $this->bufferGames->toArray();
    }

}
