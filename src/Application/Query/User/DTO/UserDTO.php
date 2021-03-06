<?php

namespace Directory\Application\Query\User\DTO;

class UserDTO
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string[] */
    private $properties;

    /**
     * @param string[] $properties
     */
    public function __construct(string $id, string $name, array $properties = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->properties = $properties;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
