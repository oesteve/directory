<?php

namespace Directory\Application\Query\User;

class GetUserById
{
    /** @var string */
    private $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
