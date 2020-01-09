<?php

declare(strict_types=1);

namespace Directory\Tests\UI\Command;

use Directory\Tests\UI\BaseFunctionalTest;

class CreateUserCommandTest extends BaseFunctionalTest
{
    public function testCreateUserAndRetrieveByItsId(): void
    {
        // Create a new user
        $output = $this->executeCommand('directory:user:create',
            [
                'userName' => 'oscar',
                '--property' => ['bar=foo'],
            ]);

        $this->assertStringContainsString('User oscar was created with id', $output);

        preg_match("/User\soscar\swas\screated\swith\sid\s([a-z,0-9,\-]+)\s/", $output, $match);
        $userId = $match[1];
        $this->assertNotNull($userId);

        // Retrieve user by its id
        $output = $this->executeCommand('directory:user:get', ['userId' => $userId]);

        $this->assertRegExp('/Id\s+([0-9,a-z,\-]+)/', $output);
        $this->assertRegExp('/Name\s+oscar/', $output);
        $this->assertRegExp('/bar\s+foo/', $output);
    }

    public function testGetUserWithInvalidId(): void
    {
        // Retrieve user
        $output = $this->executeCommand('directory:user:get', ['userId' => 'invalid-id']);
        $this->assertStringContainsString('[ERROR] User with id invalid-id not found', $output);
    }
}
