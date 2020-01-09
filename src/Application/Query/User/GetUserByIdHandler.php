<?php

namespace Directory\Application\Query\User;

use Directory\Application\Query\User\DTO\UserDTO;
use Directory\Domain\User\UserRepository;

class GetUserByIdHandler
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(GetUserById $query): UserDTO
    {
        $user = $this->userRepository->findOneById($query->getUserId());

        $props = [];

        foreach ($user->getProperties() as $property) {
            $props[$property->getName()] = $property->getValue();
        }

        return new UserDTO($user->getId(), $user->getName(), $props);
    }
}
