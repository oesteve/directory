<?php

namespace Directory\Domain\User;

class User
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var array<UserProperty> */
    private $properties = [];

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->setName($name);
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
     * @return UserProperty[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setProperty(UserProperty $userProperty): void
    {
        // remove previous properties by name if exists
        $properties = array_filter($this->properties, function (UserProperty $item) use ($userProperty) {
            return ($item->getName() === $userProperty->getName()) ? false : true;
        });

        $properties[] = $userProperty;

        $this->properties = $properties;
    }

    public function getProperty(string $name): ?UserProperty
    {
        foreach ($this->properties as $property) {
            if ($property->getName() === $name) {
                return $property;
            }
        }

        return null;
    }

    public function removeProperty(string $name): void
    {
        $this->properties = array_filter($this->properties, function (UserProperty $item) use ($name) {
            return $item->getName() !== $name;
        });
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setName(string $name): void
    {
        if (strlen($name) < 3) {
            throw new InvalidArgumentException('The name must have at least 3 characters');
        }
        $this->name = $name;
    }
}
