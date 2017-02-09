<?php

namespace ZfSnapVarConfig\Test;

use PHPUnit_Framework_TestCase;
use ZfSnapVarConfig\Exception;
use ZfSnapVarConfig\Module;
use ZfSnapVarConfig\VarConfigInterface;

class ModuleTest extends PHPUnit_Framework_TestCase
{
    protected $module;

    protected function setUp()
    {
        $this->module = new Module();
    }

    public function testPrepareConfigFromFirstElement()
    {
        $mock = $this->getMock(VarConfigInterface::class, array('getNestedKeys'));
        $mock->expects($this->any())->method('getNestedKeys')->willReturn(array(
            'sharedConfig',
        ));

        $config = array(
            'sharedConfig' => 'sharedValue',
            'awesome' => $mock,
        );
        $preparedConfig = $this->module->prepareConfig($config);

        $this->assertEquals($config['sharedConfig'], $preparedConfig['awesome']);
    }

    public function testPrepareConfigFromNestedKeys()
    {
        $mock = $this->getMock(VarConfigInterface::class, array('getNestedKeys'));
        $mock->expects($this->any())->method('getNestedKeys')->willReturn(array(
            'sharedConfig',
            'nested',
            'very',
        ));

        $config = array(
            'sharedConfig' => array(
                'nested' => array(
                    'very' => 'nestedValue',
                )
            ),
            'awesome' => $mock,
        );
        $preparedConfig = $this->module->prepareConfig($config);

        $this->assertEquals($config['sharedConfig']['nested']['very'], $preparedConfig['awesome']);
    }

    public function testPrepareConfigFromNestedVars()
    {
        $mock = $this->getMock(VarConfigInterface::class, array('getNestedKeys'));
        $mock->expects($this->any())->method('getNestedKeys')->willReturn(array(
            'sharedConfig',
            'nested',
            'very',
        ));

        $mockTwo = $this->getMock(VarConfigInterface::class, array('getNestedKeys'));
        $mockTwo->expects($this->any())->method('getNestedKeys')->willReturn(array(
            'value',
        ));

        $config = array(
            'value' => 'baz',
            'sharedConfig' => array(
                'nested' => array(
                    'very' => $mockTwo,
                )
            ),
            'awesome' => $mock,
        );
        $preparedConfig = $this->module->prepareConfig($config);

        $this->assertEquals($config['value'], $preparedConfig['awesome']);
    }

    public function testFailPrepareConfigFromEmptyArray()
    {
        $mock = $this->getMock(VarConfigInterface::class, array('getNestedKeys'));
        $mock->expects($this->any())->method('getNestedKeys')->willReturn(array());

        $config = array(
            'awesome' => $mock,
        );

        $this->setExpectedException(Exception::class, 'It is not an array or is empty');

        $this->module->prepareConfig($config);
    }

    public function testFailPrepareConfigFromNonArray()
    {
        $mock = $this->getMock(VarConfigInterface::class, array('getNestedKeys'));
        $mock->expects($this->any())->method('getNestedKeys')->willReturn('string');

        $config = array(
            'awesome' => $mock,
        );

        $this->setExpectedException(Exception::class, 'It is not an array or is empty');

        $this->module->prepareConfig($config);
    }

    public function testFailPrepareConfigFromNonExisitigKey()
    {
        $mock = $this->getMock(VarConfigInterface::class, array('getNestedKeys'));
        $mock->expects($this->any())->method('getNestedKeys')->willReturn(array(
            'baz'
        ));

        $config = array(
            'bar' => 'foo',
            'awesome' => $mock,
        );

        $this->setExpectedException(Exception::class, 'Unknown configuration key baz');

        $this->module->prepareConfig($config);
    }
}
