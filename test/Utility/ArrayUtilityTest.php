<?php

namespace JodyBoucher\Laravel\Tests\Utility;

use JodyBoucher\Laravel\Utility\ArrayUtility;
use PHPUnit\Framework\TestCase;

class ArrayUtilityTest extends TestCase
{
    public function testKeyExists()
    {
        $this->assertTrue(ArrayUtility::keyExists([1], 0));
        $this->assertTrue(ArrayUtility::keyExists([null], 0));
        $this->assertTrue(ArrayUtility::keyExists(['a' => 1], 'a'));
        $this->assertTrue(ArrayUtility::keyExists(['a' => null], 'a'));

        $this->assertFalse(ArrayUtility::keyExists([1], 1));
        $this->assertFalse(ArrayUtility::keyExists([null], 1));
        $this->assertFalse(ArrayUtility::keyExists(['a' => 1], 0));
        $this->assertFalse(ArrayUtility::keyExists(null, 0));
        $this->assertFalse(ArrayUtility::keyExists(null, null));
    }

    public function testGetValueOrDefault()
    {
        $array = ['someString' => 'abc', 'someInt' => 123, 'someArray' => [1, 2, 3], 'someNull' => null];

        // Test key exists
        $this->assertEquals(
            'abc',
            ArrayUtility::getValueOrDefault($array, 'someString', 'xyz')
        );
        $this->assertEquals(
            123,
            ArrayUtility::getValueOrDefault($array, 'someInt', 'xyz')
        );
        $this->assertEquals(
            [1, 2, 3],
            ArrayUtility::getValueOrDefault($array, 'someArray', 'xyz')
        );
        $this->assertNull(
            null,
            ArrayUtility::getValueOrDefault($array, 'someNull', 'xyz'));

        // Test key missing
        $this->assertEquals(
            '',
            ArrayUtility::getValueOrDefault($array, 'missingKey', '')
        );
        $this->assertNull(
            null,
            ArrayUtility::getValueOrDefault($array, 'missingKey', null)
        );
        $this->assertEquals(
            'xyz',
            ArrayUtility::getValueOrDefault($array, 'missingKey', 'xyz')
        );
        $this->assertEquals(
            789,
            ArrayUtility::getValueOrDefault($array, 'missingKey', 789)
        );
        $this->assertEquals(
            ['a', 1],
            ArrayUtility::getValueOrDefault($array, 'missingKey', ['a', 1])
        );

        // Test NULL key
        $this->assertEquals(
            'xyz',
            ArrayUtility::getValueOrDefault($array, null, 'xyz'));

        // Test NULL array
        $this->assertEquals(
            'xyz',
            ArrayUtility::getValueOrDefault(null, 'someString', 'xyz'));

        // Test NULL array, NULL key
        $this->assertEquals(
            'xyz',
            ArrayUtility::getValueOrDefault(null, null, 'xyz'));

        // Test empty array
        $this->assertEquals(
            'xyz',
            ArrayUtility::getValueOrDefault([], 'missingKey', 'xyz')
        );
        $this->assertEquals(
            'xyz',
            ArrayUtility::getValueOrDefault([], null, 'xyz')
        );
    }
}
