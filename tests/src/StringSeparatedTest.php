<?php

namespace ZfSnapVarConfig\Test;

use PHPUnit_Framework_TestCase;
use ZfSnapVarConfig\Exception;
use ZfSnapVarConfig\StringSeparated;
use ZfSnapVarConfig\VarConfigInterface;

class StringSeparatedTest extends PHPUnit_Framework_TestCase
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
        $keys = $object->getNestedKeys();

        $this->assertEquals(['foo', 'bar'], $keys);
    }

    public function testDefaultNoSeparated()
    {
        $object = new StringSeparated('foo');
        $keys = $object->getNestedKeys();

        $this->assertEquals(['foo'], $keys);
    }

    public function testCustomSeparator()
    {
        $object = new StringSeparated('foo|bar|baz', '|');
        $keys = $object->getNestedKeys();

        $this->assertEquals(['foo', 'bar', 'baz'], $keys);
    }

    public function testFirstParameterAllowOnlyString()
    {
        $this->setExpectedException(Exception::class);

        new StringSeparated(['foo', 'bar']);
    }

    public function testSecondParameterAllowOnlyString()
    {
        $this->setExpectedException(Exception::class);

        new StringSeparated('foo', []);
    }

    public function testLongSeparatorString()
    {
        $object = new StringSeparated('foo:::bar:::baz', ':::');
        $keys = $object->getNestedKeys();

        $this->assertEquals(['foo', 'bar', 'baz'], $keys);
    }
}
