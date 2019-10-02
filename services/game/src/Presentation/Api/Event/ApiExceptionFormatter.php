<?php

declare(strict_types=1);

namespace App\Presentation\Api\Event;

use App\Application\Service\Error\ErrorHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use DomainException;

class ApiExceptionFormatter implements EventSubscriberInterface
{
    protected $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    public static function getSubscribedEvents(): array
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getException();

        $this->errors->handle($exception);

        if (!$exception instanceof DomainException) {
            return;
        }

        $event->setResponse(new JsonResponse([
            'error' => [
                'code' => 400,
                'message' => $exception->getMessage(),
            ]
        ], 400));
    }
}
