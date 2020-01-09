<?php

namespace Directory\Infrastructure\Messenger;

use Directory\Application\Exception\ApplicationException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class ApplicationExceptionMiddleware implements MiddlewareInterface
{
    /**
     * @throws ApplicationException
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        try {
            $envelope = $stack->next()->handle($envelope, $stack);
        } catch (\Throwable $e) {
            throw ApplicationException::fromException($e);
        }

        return $envelope;
    }
}
