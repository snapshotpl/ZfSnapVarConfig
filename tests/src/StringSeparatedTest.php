<?php

namespace ZfSnapVarConfig\Test;

use PHPUnit_Framework_TestCase;
use ZfSnapVarConfig\Exception;
use ZfSnapVarConfig\StringSeparated;

class StringSeparatedTest extends PHPUnit_Framework_TestCase
{
    public function testImplementsInterface()
    {
        $object = new StringSeparated('foo.bar');

        $this->assertInstanceOf('\ZfSnapVarConfig\VarConfigInterface', $object);
    }

    public function testDefaultSeparator()
    {
        $defaultSeparator = StringSeparated::DEFAULT_SEPARATOR;
        $object = new StringSeparated('foo'. $defaultSeparator .'bar');
        $keys = $object->getNestedKeys();

        $this->assertEquals(array('foo', 'bar'), $keys);
    }

    public function testDefaultNoSeparated()
    {
        $object = new StringSeparated('foo');
        $keys = $object->getNestedKeys();

        $this->assertEquals(array('foo'), $keys);
    }

    public function testCustomSeparator()
    {
        $object = new StringSeparated('foo|bar|baz', '|');
        $keys = $object->getNestedKeys();

        $this->assertEquals(array('foo', 'bar', 'baz'), $keys);
    }

    /**
     * @expectedException Exception
     */
    public function testFirstParameterAllowOnlyString()
    {
        new StringSeparated(array('foo', 'bar'));
    }

    /**
     * @expectedException Exception
     */
    public function testSecondParameterAllowOnlyString()
    {
        new StringSeparated('foo', array());
    }

    public function testLongSeparatorString()
    {
        $object = new StringSeparated('foo:::bar:::baz', ':::');
        $keys = $object->getNestedKeys();

        $this->assertEquals(array('foo', 'bar', 'baz'), $keys);
    }
}
