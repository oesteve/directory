<?php

namespace Directory\Tests\Infrastructure\Doctrine\Repository;

use Directory\Domain\User\User;
use Directory\Domain\User\UserProperty;
use Directory\Infrastructure\Doctrine\ConnectionFactory;
use Directory\Infrastructure\Doctrine\Repository\DoctrineUserRepository;
use Directory\Infrastructure\Doctrine\Schema\SchemaProvider;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

class UserPropertiesTest extends TestCase
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var DoctrineUserRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        $connection = ConnectionFactory::buildConnection('sqlite:///:memory:');

        $schemaProvider = new SchemaProvider();
        $schema = $schemaProvider->createSchema();

        $sql = $schema->toSql($connection->getDatabasePlatform());
        foreach ($sql as $query) {
            $connection->executeQuery($query);
        }

        $repository = new DoctrineUserRepository($connection);

        $this->repository = $repository;
        $this->connection = $connection;
    }

    public function testOnRemoveUserTheUserPropertiesShouldBeDeleted(): void
    {
        $myUser = new User('anId', 'My User');
        $myUser->setProperty(new UserProperty('bar', 'foo'));

        $this->repository->save($myUser);
        $this->repository->remove($myUser->getId());

        $res = $this->connection->createQueryBuilder()
            ->select('p.name as prop_name', 'p.value as prop_value')
            ->from(DoctrineUserRepository::USER_PROPERTIES_TABLE, 'p')
            ->where('p.user_id = :id')
            ->setParameter('id', $myUser->getId())
            ->execute()
            ->fetchAll();

        $this->assertCount(0, $res);
    }
}
