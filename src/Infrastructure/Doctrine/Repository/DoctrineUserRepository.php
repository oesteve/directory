<?php

namespace Directory\Infrastructure\Doctrine\Repository;

use Directory\Domain\User\DuplicatedNameException;
use Directory\Domain\User\User;
use Directory\Domain\User\UserNotFoundException;
use Directory\Domain\User\UserProperty;
use Directory\Domain\User\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class DoctrineUserRepository implements UserRepository
{
    const USER_TABLE = 'user';
    const USER_PROPERTIES_TABLE = 'user_property';

    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user): void
    {
        try {
            try {
                $prevUser = $this->findOneById($user->getId());
                if ($prevUser != $user->getName()) {
                    $this->updateUser($user->getId(), $user->getName());
                }
                $this->updateUserProperties($prevUser, $user);
            } catch (UserNotFoundException $exception) {
                $this->createUser($user->getId(), $user->getName());
                $this->createUserProperties($user);
            }
        } catch (UniqueConstraintViolationException $exception) {
            if (strpos($exception->getMessage(), 'user.name')) {
                throw DuplicatedNameException::fromEntity($user);
            }

            throw $exception;
        }
    }

    public function remove(string $id): void
    {
        $this->connection->createQueryBuilder()
            ->delete(self::USER_TABLE)
            ->where('id = :id')
            ->setParameter('id', $id)
            ->execute();
    }

    public function findOneById(string $userId): User
    {
        $res = $this->connection->createQueryBuilder()
            ->select('u.id, u.name', 'p.name as prop_name', 'p.value as prop_value')
            ->from(self::USER_TABLE, 'u')
            ->leftJoin('u', self::USER_PROPERTIES_TABLE, 'p', 'u.id = p.user_id')
            ->where('u.id = :id')
            ->setParameter('id', $userId)
            ->execute()
            ->fetchAll();

        if (empty($res)) {
            throw UserNotFoundException::fromUserId($userId);
        }

        $user = new User($res[0]['id'], $res[0]['name']);

        foreach ($res as $value) {
            // LEFT JOIN return null when there isn't user properties
            if (null === $value['prop_name']) {
                continue;
            }
            $user->setProperty(new UserProperty($value['prop_name'], $value['prop_value']));
        }

        return $user;
    }

    /**
     * @return User[]
     */
    public function searchByPropertyValue(string $query): array
    {
        /** @var User[] $users */
        $users = [];

        $res = $this->connection->createQueryBuilder()
            ->select('u.id, u.name', 'p.name as prop_name', 'p.value as prop_value')
            ->from(self::USER_TABLE, 'u')
            ->leftJoin('u', self::USER_PROPERTIES_TABLE, 'p', 'u.id = p.user_id')
            ->where('p.value like :query')
            ->setParameter('query', "%${query}%")
            ->execute()
            ->fetchAll();

        if (empty($res)) {
            return [];
        }

        foreach ($res as $value) {
            if (!array_key_exists($value['id'], $users)) {
                $users[$value['id']] = new User($value['id'], $value['name']);
            }
            $user = $users[$value['id']];
            $user->setProperty(new UserProperty($value['prop_name'], $value['prop_value']));
        }

        return array_values($users);
    }

    private function createUserProperties(User $user): void
    {
        foreach ($user->getProperties() as $prop) {
            $this->createUserProperty($user->getId(), $prop->getName(), $prop->getValue());
        }
    }

    private function updateUserProperties(User $prevUser, User $user): void
    {
        foreach ($prevUser->getProperties() as $prevProp) {
            $propName = $prevProp->getName();
            if (!$user->getProperty($propName)) {
                $this->removeProperty($user->getId(), $propName);
            }
        }

        foreach ($user->getProperties() as $property) {
            if ($prevUser->getProperty($property->getName())) {
                $this->updateUserProperty($user->getId(), $property->getName(), $property->getValue());
            } else {
                $this->createUserProperty($user->getId(), $property->getName(), $property->getValue());
            }
        }
    }

    private function updateUser(string $userId, string $name): void
    {
        $this->connection->createQueryBuilder()
            ->update(self::USER_TABLE)
            ->set('name', ':name')
            ->where('id = :id')
            ->setParameters([
                'id' => $userId,
                'name' => $name,
            ])
            ->execute();
    }

    private function updateUserProperty(string $userId, string $name, string $value): void
    {
        $this->connection->createQueryBuilder()
            ->update(self::USER_PROPERTIES_TABLE)
            ->set('value', ':value')
            ->where('user_id = :userId')
            ->andWhere('name = :name')
            ->setParameters([
                'userId' => $userId,
                'name' => $name,
                'value' => $value,
            ])
            ->execute();
    }

    private function createUser(string $userId, string $name): void
    {
        $this->connection->createQueryBuilder()
            ->insert(self::USER_TABLE)
            ->values([
                'id' => ':id',
                'name' => ':name',
            ])
            ->setParameters([
                'id' => $userId,
                'name' => $name,
            ])
            ->execute();
    }

    private function createUserProperty(string $userId, string $name, string $value): void
    {
        $this->connection->createQueryBuilder()
            ->insert(self::USER_PROPERTIES_TABLE)
            ->values([
                'user_id' => ':userId',
                'name' => ':name',
                'value' => ':value',
            ])
            ->setParameters([
                'userId' => $userId,
                'name' => $name,
                'value' => $value,
            ])
            ->execute();
    }

    private function removeProperty(string $userId, string $name): void
    {
        $this->connection->createQueryBuilder()
            ->delete(self::USER_PROPERTIES_TABLE)
            ->where('user_id = :userId')
            ->andWhere('name = :name')
            ->setParameters([
                'userId' => $userId,
                'name' => $name,
            ])
            ->execute();
    }
}
