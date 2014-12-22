<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ZfSnapVarConfig\Test;

/**
 * ModuleTest
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class ModuleTest extends \PHPUnit_Framework_TestCase
{
    protected $module;

    protected function setUp()
    {
        $this->module = new \ZfSnapVarConfig\Module();
    }

    public function testPrepareConfigFromFirstElement()
    {
        $mock = $this->getMock('ZfSnapVarConfig\VarConfigInterface', array('getNestedKeys'));
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
        $mock = $this->getMock('ZfSnapVarConfig\VarConfigInterface', array('getNestedKeys'));
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
        $mock = $this->getMock('ZfSnapVarConfig\VarConfigInterface', array('getNestedKeys'));
        $mock->expects($this->any())->method('getNestedKeys')->willReturn(array(
            'sharedConfig',
            'nested',
            'very',
        ));

        $mockTwo = $this->getMock('ZfSnapVarConfig\VarConfigInterface', array('getNestedKeys'));
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

    /**
     * @expectedException \ZfSnapVarConfig\Exception
     * @expectedExceptionMessage It is not an array or is empty
     */
    public function testFailPrepareConfigFromEmptyArray()
    {
        $mock = $this->getMock('ZfSnapVarConfig\VarConfigInterface', array('getNestedKeys'));
        $mock->expects($this->any())->method('getNestedKeys')->willReturn(array());

        $config = array(
            'awesome' => $mock,
        );
        $this->module->prepareConfig($config);
    }

    /**
     * @expectedException \ZfSnapVarConfig\Exception
     * @expectedExceptionMessage It is not an array or is empty
     */
    public function testFailPrepareConfigFromNonArray()
    {
        $mock = $this->getMock('ZfSnapVarConfig\VarConfigInterface', array('getNestedKeys'));
        $mock->expects($this->any())->method('getNestedKeys')->willReturn('string');

        $config = array(
            'awesome' => $mock,
        );
        $this->module->prepareConfig($config);
    }

    /**
     * @expectedException \ZfSnapVarConfig\Exception
     * @expectedExceptionMessage Unknown configuration key baz
     */
    public function testFailPrepareConfigFromNonExisitigKey()
    {
        $mock = $this->getMock('ZfSnapVarConfig\VarConfigInterface', array('getNestedKeys'));
        $mock->expects($this->any())->method('getNestedKeys')->willReturn(array(
            'baz'
        ));

        $config = array(
            'bar' => 'foo',
            'awesome' => $mock,
        );
        $this->module->prepareConfig($config);
    }

}
