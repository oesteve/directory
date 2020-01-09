<?php

namespace Directory\Infrastructure\Messenger;

use Directory\Application\Command\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerCommandBus implements CommandBus
{
    /** @var MessageBusInterface */
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function dispatch($command): void
    {
        $this->messageBus->dispatch($command);
    }
}
