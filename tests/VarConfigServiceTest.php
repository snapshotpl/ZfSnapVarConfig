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

    public function testFailPrepareConfigFromNonExisitigKey()
    {
        $config = [
            'bar' => 'foo',
            'awesome' => new ArgsList('baz'),
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unknown configuration key baz');

        $this->service->replace($config);
    }

    public function testFailPrepareConfigFromNonExisitigNestedKey()
    {
        $config = [
            'bar' => 'foo',
            'awesome' => new ArgsList('bar', 'baz'),
        ];

        $this->setExpectedException(Exception::class, 'Unknown configuration key bar->baz');

        $this->service->replace($config);
    }

    public function testWorksWithNumeric()
    {
        $config = [
            'bar' => [
                'first-element'
            ],
            'awesome' => new ArgsList('bar', 0),
        ];

        $preparedConfig = $this->service->replace($config);

        $this->assertSame(['bar' => ['first-element'], 'awesome' => 'first-element'], $preparedConfig);
    }

    public function testWorksWithFloats()
    {
        $config = [
            0.5 => 'first-element',
            'awesome' => new ArgsList(0.5),
        ];

        $preparedConfig = $this->service->replace($config);

        $this->assertSame([0.5 => 'first-element', 'awesome' => 'first-element'], $preparedConfig);
    }
}
