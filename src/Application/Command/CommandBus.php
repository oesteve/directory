<?php

namespace Directory\Application\Command;

use Directory\Application\Exception\ApplicationException;

interface CommandBus
{
    /**
     * @throws ApplicationException
     *
     * @param object $command
     */
    public function dispatch($command): void;
}
