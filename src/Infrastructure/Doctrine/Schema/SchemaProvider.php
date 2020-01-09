<?php

namespace Directory\Infrastructure\Doctrine\Schema;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\Provider\SchemaProviderInterface;

final class SchemaProvider implements SchemaProviderInterface
{
    public function createSchema(): Schema
    {
        $schema = new Schema();

        $user = $schema->createTable('user');
        $user->addColumn('id', 'string');
        $user->addColumn('name', 'string', ['notnull' => false]);
        $user->setPrimaryKey(['id']);
        $user->addUniqueIndex(['name']);

        $userProperty = $schema->createTable('user_property');
        $userProperty->addColumn('user_id', 'string');
        $userProperty->addColumn('name', 'string', ['notnull' => false]);
        $userProperty->addColumn('value', 'string', ['notnull' => false]);
        $userProperty->addForeignKeyConstraint($user, ['user_id'], ['id']);
        $userProperty->addUniqueIndex(['user_id', 'name']);

        return $schema;
    }
}
