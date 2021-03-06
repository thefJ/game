<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Game\League\Merge;

use App\Domain\Model\Game\Service\League\Merge;

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
