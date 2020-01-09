<?php

namespace Directory\Tests\Infrastructure\Doctrine\Repository;

use Directory\Domain\User\UserRepository;
use Directory\Infrastructure\Doctrine\ConnectionFactory;
use Directory\Infrastructure\Doctrine\Repository\DoctrineUserRepository;
use Directory\Infrastructure\Doctrine\Schema\SchemaProvider;
use Directory\Tests\Domain\User\UserRepositoryTest;

class DoctrineUserRepositoryTest extends UserRepositoryTest
{
    public function getEmptyRepository(): UserRepository
    {
        $connection = ConnectionFactory::buildConnection('sqlite:///:memory:');

        $schemaProvider = new SchemaProvider();
        $schema = $schemaProvider->createSchema();

        $sql = $schema->toSql($connection->getDatabasePlatform());
        foreach ($sql as $query) {
            $connection->executeQuery($query);
        }

        $repository = new DoctrineUserRepository($connection);

        return  $repository;
    }
}
