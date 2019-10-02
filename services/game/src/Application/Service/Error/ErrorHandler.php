<?php

declare(strict_types=1);

namespace App\Application\Service\Error;

use Psr\Log\LoggerInterface;
use Exception;

class ErrorHandler
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Exception $e): void
    {
        $this->logger->warning($e->getMessage(), ['exception' => $e]);
    }
}
