<?php

namespace Directory\Domain\User;

class DuplicatedNameException extends \Exception
{
    public static function fromEntity(User $user): self
    {
        return new self(sprintf('The name %s already exists', $user->getName()));
    }
}
