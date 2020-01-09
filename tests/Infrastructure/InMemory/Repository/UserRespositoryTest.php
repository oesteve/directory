<?php

namespace Directory\Tests\Infrastructure\InMemory\Repository;

use Directory\Domain\User\UserRepository;
use Directory\Infrastructure\InMemory\Repository\InMemoryUserRepository;
use Directory\Tests\Domain\User\UserRepositoryTest;

class UserRespositoryTest extends UserRepositoryTest
{
    public function getEmptyRepository(): UserRepository
    {
        return new InMemoryUserRepository();
    }
}
