<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Entity;

use App\Domain\Model\AggregateRoot;
use App\Domain\Model\EventsTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="league", indexes={@ORM\Index(name="sport_transliterated_name_idx", columns={"transliterated_name"})}))
 */
class League implements AggregateRoot
{
    use EventsTrait;

    /**
     * @var Uuid
     * @ORM\Column(type="uuid")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    private $transliteratedName;

    /**
     * @var Sport
     * @ORM\ManyToOne(targetEntity="Sport")
     * @ORM\JoinColumn(name="sport_id", referencedColumnName="id", nullable=false)
     */
    private $sport;

    public function __construct(Uuid $id, string $name, string $transliteratedName, Sport $sport)
    {
        $this->id = $id;
        $this->name = $name;
        $this->transliteratedName = $transliteratedName;
        $this->sport = $sport;
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTransliteratedName(): string
    {
        return $this->transliteratedName;
    }

    /**
     * @return Sport
     */
    public function getSport(): Sport
    {
        return $this->sport;
    }
}
