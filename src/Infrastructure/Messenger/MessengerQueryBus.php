<?php

namespace Directory\Infrastructure\Messenger;

use Directory\Application\Query\QueryBus;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerQueryBus implements QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @param object $command
     *
     * @return mixed|object
     */
    public function query($command)
    {
        return $this->handle($command);
    }
}
