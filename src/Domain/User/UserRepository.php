<?php

namespace Directory\Domain\User;

interface UserRepository
{
    /**
     * @throws DuplicatedNameException
     */
    public function save(User $user): void;

    public function remove(string $id): void;

    /**
     * @throws UserNotFoundException
     */
    public function findOneById(string $userId): User;

    /**
     * @return User[]
     */
    public function searchByPropertyValue(string $query): array;
}
