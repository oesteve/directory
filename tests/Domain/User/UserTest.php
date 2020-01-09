<?php

namespace Directory\Tests\Domain\User;

use Directory\Domain\User\User;
use Directory\Domain\User\UserProperty;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testOnAddUserPropertiesShouldBePresent(): void
    {
        $user = new User('anId', 'Bob');
        $user->setProperty(new UserProperty('bar', 'foo'));

        $this->assertCount(1, $user->getProperties());
    }

    public function testOnOverwriteUserPropertiesValueShouldBeUpdated(): void
    {
        $user = new User('anId', 'Bob');
        $user->setProperty(new UserProperty('color', 'red'));
        $user->setProperty(new UserProperty('color', 'pink'));

        $this->assertCount(1, $user->getProperties());

        $color = $user->getProperty('color');

        $this->assertNotNull($color);
        $this->assertEquals('pink', $color->getValue());
    }
}
