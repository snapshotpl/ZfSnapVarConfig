<?php

namespace ZfSnapVarConfig\Test;

use PHPUnit\Framework\TestCase;
use ZfSnapVarConfig\ArgsList;
use ZfSnapVarConfig\Exception;
use ZfSnapVarConfig\VarConfigInterface;
use ZfSnapVarConfig\VarConfigService;

class VarConfigServiceTest extends TestCase
{
    protected $service;

    protected function setUp()
    {
        $this->service = new VarConfigService();
    }

    public function testPrepareConfigFromFirstElement()
    {
        $config = [
            'sharedConfig' => 'sharedValue',
            'awesome' => new ArgsList('sharedConfig'),
        ];
        $preparedConfig = $this->service->replace($config);

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
            'awesome' => new ArgsList('sharedConfig', 'nested', 'very'),
        ];
        $preparedConfig = $this->service->replace($config);

        $this->assertSame($config['sharedConfig']['nested']['very'], $preparedConfig['awesome']);
    }

    public function testPrepareConfigFromNestedVars()
    {
        $config = [
            'value' => 'baz',
            'sharedConfig' => [
                'nested' => [
                    'very' => new ArgsList('value'),
                ],
            ],
            'awesome' => new ArgsList('sharedConfig', 'nested', 'very'),
        ];
        $preparedConfig = $this->service->replace($config);

        $this->assertSame($config['value'], $preparedConfig['awesome']);
    }

    public function testPrepareConfigFromNestedVars2()
    {
        $config = [
            'value' => 'baz',
            'sharedConfig' => [
                'nesteded' => new ArgsList('sharedConfig', 'nested'),
                'nested' => [
                    'very' => new ArgsList('value'),
                ],
            ],
        ];
        $preparedConfig = $this->service->replace($config);

        $this->assertSame(['very' => 'baz'], $preparedConfig['sharedConfig']['nesteded']);
    }

    public function testFailPrepareConfigFromEmptyArray()
    {
        $config = [
            'awesome' => new ArgsList(),
        ];

        $this->setExpectedException(Exception::class, 'It is not an array or is empty');

        $this->service->replace($config);
    }

    public function testFailPrepareConfigFromNonArray()
    {
        $mock = $this->createMock(VarConfigInterface::class, ['getNestedKeys']);
        $mock->method('getNestedKeys')->willReturn('string');

        $config = [
            'awesome' => $mock,
        ];

        $this->setExpectedException(Exception::class, 'It is not an array or is empty');

        $this->service->replace($config);
    }

    public function testFailPrepareConfigFromNonExisitigKey()
    {
        $mock = $this->createMock(VarConfigInterface::class, ['getNestedKeys']);
        $mock->expects($this->any())->method('getNestedKeys')->willReturn(array(
        ));

        $config = [
            'bar' => 'foo',
            'awesome' => new ArgsList('baz'),
        ];

        $this->setExpectedException(Exception::class, 'Unknown configuration key baz');

        $this->service->replace($config);
    }

    public function testFailPrepareConfigFromNonExisitigNestedKey()
    {
        $mock = $this->createMock(VarConfigInterface::class, ['getNestedKeys']);
        $mock->expects($this->any())->method('getNestedKeys')->willReturn(array(
        ));

        $config = [
            'bar' => 'foo',
            'awesome' => new ArgsList('bar', 'baz'),
        ];

        $this->setExpectedException(Exception::class, 'Unknown configuration key bar->baz');

        $this->service->replace($config);
    }
}