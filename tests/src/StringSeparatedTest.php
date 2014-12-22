<?php

namespace ZfSnapVarConfig\Test;

/**
 * StringSeparatedTest
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class StringSeparatedTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsInterface()
    {
        $object = new \ZfSnapVarConfig\StringSeparated('foo.bar');

        $this->assertInstanceOf('\ZfSnapVarConfig\VarConfigInterface', $object);
    }

    public function testDefaultSeparator()
    {
        $defaultSeparator = \ZfSnapVarConfig\StringSeparated::DEFAULT_SEPARATOR;
        $object = new \ZfSnapVarConfig\StringSeparated('foo'. $defaultSeparator .'bar');
        $keys = $object->getNestedKeys();

        $this->assertEquals(array('foo', 'bar'), $keys);
    }

    public function testDefaultNoSeparated()
    {
        $object = new \ZfSnapVarConfig\StringSeparated('foo');
        $keys = $object->getNestedKeys();

        $this->assertEquals(array('foo'), $keys);
    }

    public function testCustomSeparator()
    {
        $object = new \ZfSnapVarConfig\StringSeparated('foo|bar|baz', '|');
        $keys = $object->getNestedKeys();

        $this->assertEquals(array('foo', 'bar', 'baz'), $keys);
    }

    /**
     * @expectedException ZfSnapVarConfig\Exception
     */
    public function testFirstParameterAllowOnlyString()
    {
        new \ZfSnapVarConfig\StringSeparated(array('foo', 'bar'));
    }

    /**
     * @expectedException ZfSnapVarConfig\Exception
     */
    public function testSecondParameterAllowOnlyString()
    {
        new \ZfSnapVarConfig\StringSeparated('foo', array());
    }

    public function testLongSeparatorString()
    {
        $object = new \ZfSnapVarConfig\StringSeparated('foo:::bar:::baz', ':::');
        $keys = $object->getNestedKeys();

        $this->assertEquals(array('foo', 'bar', 'baz'), $keys);
    }
}
