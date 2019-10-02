<?php

declare(strict_types=1);

namespace App\Presentation\Api\Service;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationFormatter
{
    public function format(ConstraintViolationListInterface $violationList): array {
        $formattedViolationList = [];
        for ($i = 0; $i < $violationList->count(); $i++) {
            $violation = $violationList->get($i);
            $formattedViolationList[] = [$violation->getPropertyPath() => $violation->getMessage()];
        }

        return $formattedViolationList;
    }
}
