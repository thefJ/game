<?php

declare(strict_types=1);

namespace App\Infrastructure\Event\Dispatcher;

use App\Domain\Model\EventDispatcher;
use App\Infrastructure\Event\Dispatcher\Message\Message;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerEventDispatcher implements EventDispatcher
{
    private $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function dispatch(array $events): void
    {
        foreach ($events as $event) {
            $this->bus->dispatch(new Message($event));
        }
    }
}
