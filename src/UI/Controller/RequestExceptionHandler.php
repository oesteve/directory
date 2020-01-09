<?php

namespace Directory\UI\Controller;

use Directory\Application\Exception\ApplicationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestExceptionHandler implements EventSubscriberInterface
{
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();

        if (!$exception instanceof ApplicationException) {
            return;
        }

        // Por ahora solo aceptamos JSON en la app
        $event->setResponse(new JsonResponse(
            ['message' => $exception->getMessage()],
            Response::HTTP_INTERNAL_SERVER_ERROR)
        );

        $event->stopPropagation();
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents()
    {
        return [KernelEvents::EXCEPTION => 'onKernelException'];
    }
}
