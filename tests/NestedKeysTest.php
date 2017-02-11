<?php

namespace ZfSnapVarConfig\Test;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ZfSnapVarConfig\NestedKeys;

class NestedKeysTest extends TestCase
{
    public function testCorrectKeys()
    {
        $nestedKeys = new NestedKeys(['boo', 123, 12.3, true]);

        $this->assertSame(['boo', 123, 12.3, true], $nestedKeys->getKeys());
    }

    public function testCannotCreateWithKeys()
    {
        $this->expectException(InvalidArgumentException::class);

        new NestedKeys(['foo' => 'boo']);
    }

    public function testCannotCreateWithEmptyKeys()
    {
        $this->expectException(InvalidArgumentException::class);

        new NestedKeys([]);
    }

    public function testCannotCreateWithNonscalarKeys()
    {
        $this->expectException(InvalidArgumentException::class);

        new NestedKeys([[]]);
    }
}
