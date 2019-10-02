<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Game\BufferGame\Create;

use App\Domain\Model\Game\Service\BufferGame\Create;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeImmutable;

class Command implements Create\Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $sport;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $league;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $hostTeam;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $guestTeam;

    /**
     * @var DateTimeImmutable
     * @Assert\DateTime()
     */
    public $date;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $language;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $source;

    public function __construct(array $data)
    {
        $this->sport = $data['sport'] ?? '';
        $this->league = $data['league'] ?? '';
        $this->hostTeam = $data['host_team'] ?? '';
        $this->guestTeam = $data['guest_team'] ?? '';
        $this->date = $data['date'] ? new \DateTimeImmutable($data['date']) : '';
        $this->language = $data['language'] ?? '';
        $this->source = $data['source'] ?? '';
    }
}
