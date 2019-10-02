<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Entity;

use Ramsey\Uuid\Uuid as ExternalUuid;
use Webmozart\Assert\Assert;

class Uuid
{
    private $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        $this->value = $value;
    }

    public static function generate(): self
    {
        return new self(ExternalUuid::uuid4()->toString());
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
