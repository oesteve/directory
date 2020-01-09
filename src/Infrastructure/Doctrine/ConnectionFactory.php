<?php

namespace Directory\Infrastructure\Doctrine;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class ConnectionFactory
{
    public static function buildConnection(string $url): Connection
    {
        $config = new Configuration();
        $connectionParams = [
            'url' => $url,
        ];

        $conn = DriverManager::getConnection($connectionParams, $config);

        return $conn;
    }
}
