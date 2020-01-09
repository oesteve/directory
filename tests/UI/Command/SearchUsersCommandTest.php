<?php

namespace Directory\Tests\UI\Command;

use Directory\Application\Fixture\UserFixtures;
use Directory\Tests\UI\BaseFunctionalTest;

class SearchUsersCommandTest extends BaseFunctionalTest
{
    public function testAddUsersAndFindIt(): void
    {
        // Add provided data
        foreach (UserFixtures::sampleData() as $user) {
            array_walk($user[1], function (&$value, $key) {
                $value = sprintf('%s=%s', $key, $value);
            });

            $this->executeCommand('directory:user:create',
                ['userName' => $user[0], '--property' => $user[1]]
            );
        }

        // Search users
        $output = $this->executeCommand('directory:user:search', ['query' => 'azul']);

        $this->assertStringContainsString('[OK] 2 users found', $output);
        $this->assertStringContainsString('Juan', $output);
        $this->assertStringContainsString('Irene', $output);
    }
}
