<?php

namespace ZfSnapVarConfig\Test;

use PHPUnit\Framework\TestCase;
use ZfSnapVarConfig\InvalidArgumentException;
use ZfSnapVarConfig\StringSeparated;
use ZfSnapVarConfig\VarConfigInterface;

class StringSeparatedTest extends TestCase
{
    public function testImplementsInterface()
    {
        $object = new StringSeparated('foo.bar');

        $this->assertInstanceOf(VarConfigInterface::class, $object);
    }

    public function testDefaultSeparator()
    {
        $defaultSeparator = StringSeparated::DEFAULT_SEPARATOR;
        $object = new StringSeparated('foo'. $defaultSeparator .'bar');
        $nestedKeys = $object->getNestedKeys();

        $this->assertSame(['foo', 'bar'], $nestedKeys->getKeys());
    }

    public function testDefaultNoSeparated()
    {
        $object = new StringSeparated('foo');
        $nestedKeys = $object->getNestedKeys();

        $this->assertSame(['foo'], $nestedKeys->getKeys());
    }

    public function testCannotCreateWithEmptyString()
    {
        $this->expectException(InvalidArgumentException::class);

        new StringSeparated('');
    }

    public function testCannotCreateWithEmptySeparator()
    {
        $this->expectException(InvalidArgumentException::class);

        new StringSeparated('foo.bar', '');
    }

    public function testCustomSeparator()
    {
        $object = new StringSeparated('foo|bar|baz', '|');
        $nestedKeys = $object->getNestedKeys();

        $this->assertSame(['foo', 'bar', 'baz'], $nestedKeys->getKeys());
    }

    public function testLongSeparatorString()
    {
        $object = new StringSeparated('foo:::bar:::baz', ':::');
        $nestedKeys = $object->getNestedKeys();

        $this->assertSame(['foo', 'bar', 'baz'], $nestedKeys->getKeys());
    }
}
