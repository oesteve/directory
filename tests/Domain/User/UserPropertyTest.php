<?php

namespace Directory\Tests\Domain\User;

use Directory\Domain\User\UserProperty;
use PHPUnit\Framework\TestCase;

class UserPropertyTest extends TestCase
{
    public function testOnTwoPropertiesHasSameValuesShouldBeEquals(): void
    {
        $prop1 = new UserProperty('bar', 'foo');
        $prop2 = new UserProperty('bar', 'foo');

        $this->assertEquals($prop1, $prop2);
        $this->assertEquals(strval($prop1), strval($prop2));
    }

    public function testOnTwoPropertiesCollectionHasSameValuesShouldBeEquals(): void
    {
        $array1 = [
            new UserProperty('a', 'a'),
            new UserProperty('b', 'b'),
        ];
        $array2 = [
            new UserProperty('a', 'a'),
            new UserProperty('b', 'b'),
        ];
        $this->assertEquals([], array_diff($array2, $array1));

        $array2[] = new UserProperty('c', 'c');
        $diff = array_diff($array2, $array1);
        $this->assertEquals([new UserProperty('c', 'c')], array_values($diff));

        $diff = array_diff($array2, $array1);
        $this->assertEquals([new UserProperty('c', 'c')], array_values($diff));
    }

    public function testIfUserPropertyIsPartOfCollectionInArrayFunctionShouldBeFindIt(): void
    {
        $array = [
            new UserProperty('a', 'a'),
            new UserProperty('b', 'b'),
        ];

        $this->assertTrue(in_array(new UserProperty('b', 'b'), $array));
        $this->assertFalse(in_array(new UserProperty('c', 'c'), $array));
    }
}
