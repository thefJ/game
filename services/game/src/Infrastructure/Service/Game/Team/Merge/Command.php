<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Game\Team\Merge;

use App\Domain\Model\Game\Service\Team\Merge;

use Symfony\Component\Validator\Constraints as Assert;

class Command implements Merge\Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
