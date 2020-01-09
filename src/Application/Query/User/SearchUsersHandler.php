<?php

namespace Directory\Application\Query\User;

use Directory\Application\Query\User\DTO\UserDTO;
use Directory\Domain\User\User;
use Directory\Domain\User\UserRepository;

class SearchUsersHandler
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return UserDTO[]
     */
    public function __invoke(SearchUsers $query): array
    {
        $usersFound = $this->userRepository->searchByPropertyValue($query->getQuery());

        return array_map(function (User $user) {
            $props = [];

            foreach ($user->getProperties() as $property) {
                $props[$property->getName()] = $property->getValue();
            }

            return new UserDTO($user->getId(), $user->getName(), $props);
        }, $usersFound);
    }
}
