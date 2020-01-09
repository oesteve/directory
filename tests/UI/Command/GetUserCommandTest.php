<?php

namespace Directory\Tests\UI\Command;

use Directory\Tests\UI\BaseFunctionalTest;

class GetUserCommandTest extends BaseFunctionalTest
{
    public function testGetUserWithInvalidId(): void
    {
        // Retrieve user
        $output = $this->executeCommand('directory:user:get', ['userId' => 'invalid-id']);
        $this->assertStringContainsString('[ERROR] User with id invalid-id not found', $output);
    }
}
