<?php

namespace Directory\Infrastructure\InMemory\Repository;

use Directory\Domain\User\DuplicatedNameException;
use Directory\Domain\User\User;
use Directory\Domain\User\UserNotFoundException;
use Directory\Domain\User\UserProperty;
use Directory\Domain\User\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    /** @var User[] */
    private $users = [];

    public function save(User $user): void
    {
        foreach ($this->users as $storedUser) {
            if ($storedUser->getId() === $user->getId()) {
                continue;
            }

            if ($storedUser->getName() === $user->getName()) {
                throw DuplicatedNameException::fromEntity($user);
            }
        }
        $this->users[$user->getId()] = $user;
    }

    public function remove(string $id): void
    {
        if (!array_key_exists($id, $this->users)) {
            return;
        }

        unset($this->users[$id]);
    }

    public function findOneById(string $userId): User
    {
        if (!array_key_exists($userId, $this->users)) {
            throw UserNotFoundException::fromUserId($userId);
        }

        return $this->users[$userId];
    }

    /**
     * @return array<User>
     */
    public function searchByPropertyValue(string $query): array
    {
        $matches = [];
        foreach ($this->users as $user) {
            $userProps = $user->getProperties();
            if ($this->matchByPropertyValue($userProps, $query)) {
                $matches[] = $user;
            }
        }

        return  $matches;
    }

    /**
     * @param array<UserProperty> $userProps
     */
    private function matchByPropertyValue(array $userProps, string $query): bool
    {
        /** @var UserProperty $prop */
        foreach ($userProps as $prop) {
            $pattern = "/${query}/";
            $value = $prop->getValue();

            if (1 === preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }
}
