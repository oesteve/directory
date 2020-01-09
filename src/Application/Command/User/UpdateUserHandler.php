<?php

namespace Directory\Application\Command\User;

use Directory\Domain\User\UserProperty;
use Directory\Domain\User\UserRepository;

class UpdateUserHandler
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

    public function __invoke(UpdateUser $updateUser): void
    {
        $user = $this->userRepository->findOneById($updateUser->getId());

        $user->setName($updateUser->getName());
        foreach ($updateUser->getProperties() as $name => $value) {
            $user->setProperty(new UserProperty($name, $value));
        }

        $this->userRepository->save($user);
    }
}
