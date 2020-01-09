<?php

namespace Directory\Application\Command\User;

use Directory\Domain\User\User;
use Directory\Domain\User\UserProperty;
use Directory\Domain\User\UserRepository;

class CreateUserHandler
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * SetUserHandler constructor.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(CreateUser $createUser): void
    {
        $user = new User($createUser->getId(), $createUser->getName());

        foreach ($createUser->getProperties() as $name => $value) {
            $user->setProperty(new UserProperty($name, $value));
        }

        $this->userRepository->save($user);
    }
}
