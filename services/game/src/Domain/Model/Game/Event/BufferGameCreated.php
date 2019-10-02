<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Event;

use App\Domain\Model\Game\Entity\BufferGame;

class BufferGameCreated
{
    public $sport;
    public $league;
    public $hostTeam;
    public $guestTeam;
    public $date;
    public $language;
    public $source;
    public $hash;

    public function __construct(BufferGame $bufferGame) {
        $this->hash = $bufferGame->getHash();
        $this->sport = $bufferGame->getSport();
        $this->league = $bufferGame->getLeague();
        $this->hostTeam = $bufferGame->getHostTeam();
        $this->guestTeam = $bufferGame->getGuestTeam();
        $this->date = $bufferGame->getDate();
        $this->language = $bufferGame->getLanguage();
        $this->source = $bufferGame->getSource();
    }
}
