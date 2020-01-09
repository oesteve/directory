<?php

namespace Directory\Application\Exception;

class ApplicationException extends \Exception
{
    public static function fromException(\Throwable $e): self
    {
        return new self($e->getMessage());
    }
}
