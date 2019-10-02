<?php

declare(strict_types=1);

namespace App\Application\Service;

use Transliterator as Transliter;

class Transliterator
{
    private const RULES = 'Any-Latin; Lower()';

    private $transliterator;

    public function __construct()
    {
        $this->transliterator = Transliter::create(self::RULES);
    }

    public function transliterate(string $string)
    {
        return $this->transliterator->transliterate($string);
    }
}
