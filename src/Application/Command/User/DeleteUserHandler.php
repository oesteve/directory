<?php

namespace Directory\Application\Command\User;

use Directory\Domain\User\UserRepository;

class DeleteUserHandler
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(DeleteUser $deleteUser): void
    {
        $this->userRepository->remove($deleteUser->getId());
    }
}
