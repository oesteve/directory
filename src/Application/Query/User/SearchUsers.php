<?php

namespace Directory\Application\Query\User;

class SearchUsers
{
    /** @var string */
    private $query;

    public function __construct(string $query)
    {
        $this->query = $query;
    }

    public function getQuery(): string
    {
        return $this->query;
    }
}
