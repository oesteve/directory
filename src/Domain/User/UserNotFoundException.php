<?php

namespace Directory\Domain\User;

class UserNotFoundException extends \Exception
{
    public static function fromUserId(string $userId): self
    {
        return new self("User with id $userId not found");
    }
}
