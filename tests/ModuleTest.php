<?php

namespace ZfSnapVarConfig\Test;

use PHPUnit_Framework_TestCase;
use ZfSnapVarConfig\ArrayList;
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
        $config = [
            'sharedConfig' => 'sharedValue',
            'awesome' => new ArrayList(['sharedConfig']),
        ];
        $preparedConfig = $this->module->prepareConfig($config);

        $this->assertSame($config['sharedConfig'], $preparedConfig['awesome']);
    }

    public function testPrepareConfigFromNestedKeys()
    {
        $config = [
            'sharedConfig' => [
                'nested' => [
                    'very' => 'nestedValue',
                ],
            ],
            'awesome' => new ArrayList(['sharedConfig', 'nested', 'very']),
        ];
        $preparedConfig = $this->module->prepareConfig($config);

        $this->assertSame($config['sharedConfig']['nested']['very'], $preparedConfig['awesome']);
    }

    public function testPrepareConfigFromNestedVars()
    {
        $config = [
            'value' => 'baz',
            'sharedConfig' => [
                'nested' => [
                    'very' => new ArrayList(['value']),
                ],
            ],
            'awesome' => new ArrayList(['sharedConfig', 'nested', 'very']),
        ];
        $preparedConfig = $this->module->prepareConfig($config);

        $this->assertSame($config['value'], $preparedConfig['awesome']);
    }

    public function testPrepareConfigFromNestedVars2()
    {
        $config = [
            'value' => 'baz',
            'sharedConfig' => [
                'nesteded' => new ArrayList(['sharedConfig', 'nested']),
                'nested' => [
                    'very' => new ArrayList(['value']),
                ],
            ],
        ];
        $preparedConfig = $this->module->prepareConfig($config);

        $this->assertSame(['very' => 'baz'], $preparedConfig['sharedConfig']['nesteded']);
    }

    public function testFailPrepareConfigFromEmptyArray()
    {
        $config = [
            'awesome' => new ArrayList([]),
        ];

        $this->setExpectedException(Exception::class, 'It is not an array or is empty');

        $this->module->prepareConfig($config);
    }

    public function testFailPrepareConfigFromNonArray()
    {
        $mock = $this->createMock(VarConfigInterface::class, array('getNestedKeys'));
        $mock->method('getNestedKeys')->willReturn('string');

        $config = [
            'awesome' => $mock,
        ];

        $this->setExpectedException(Exception::class, 'It is not an array or is empty');

        $this->module->prepareConfig($config);
    }

    public function testFailPrepareConfigFromNonExisitigKey()
    {
        $mock = $this->createMock(VarConfigInterface::class, array('getNestedKeys'));
        $mock->expects($this->any())->method('getNestedKeys')->willReturn(array(
        ));

        $config = array(
            'bar' => 'foo',
            'awesome' => new ArrayList(['baz']),
        );

        $this->setExpectedException(Exception::class, 'Unknown configuration key baz');

        $this->module->prepareConfig($config);
    }
}
