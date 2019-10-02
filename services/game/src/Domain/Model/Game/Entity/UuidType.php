<?php

declare(strict_types=1);

namespace App\Domain\Model\Game\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class UuidType extends GuidType
{
    public const NAME = 'uuid';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof Uuid ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new Uuid($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
