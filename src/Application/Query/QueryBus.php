<?php

namespace Directory\Application\Query;

use Directory\Application\Exception\ApplicationException;

interface QueryBus
{
    /**
     * @param object $query
     *
     * @throws ApplicationException
     *
     * @return object
     */
    public function query($query);
}
