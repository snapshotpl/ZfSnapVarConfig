<?php

namespace ZfSnapVarConfig\Test;

use PHPUnit\Framework\TestCase;
use ZfSnapVarConfig\ArgsList;
use ZfSnapVarConfig\Exception;
use ZfSnapVarConfig\Value\Path;
use ZfSnapVarConfig\VarConfigInterface;
use ZfSnapVarConfig\VarConfigService;

class VarConfigServiceTest extends TestCase
{
    /**
     * @var VarConfigService
     */
    protected $service;

    protected function setUp(): void
    {
        $this->service = new VarConfigService();
    }

    public function testPrepareConfigFromFirstElement()
    {
        $config = [
            'sharedConfig' => 'sharedValue',
            'awesome' => new Path('sharedConfig'),
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
            'awesome' => new Path('sharedConfig', 'nested', 'very'),
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
                    'very' => new Path('value'),
                ],
            ],
            'awesome' => new Path('sharedConfig', 'nested', 'very'),
        ];
        $preparedConfig = $this->service->replace($config);

        $this->assertSame('baz', $preparedConfig['awesome']);
    }

    public function testPrepareConfigFromCachedNestedVars()
    {
        $config = [
            'awesome' => new Path('sharedConfig', 'nested', 'very'),
            'awesome2' => new Path('sharedConfig', 'nested', 'very'),
            'value' => 'baz',
            'sharedConfig' => [
                'nested' => [
                    'very' => new Path('value'),
                ],
            ],
        ];
        $preparedConfig = $this->service->replace($config);

        $this->assertSame('baz', $preparedConfig['awesome2']);
    }

    public function testPrepareConfigFromNestedVars2()
    {
        $config = [
            'value' => 'baz',
            'sharedConfig' => [
                'nesteded' => new Path('sharedConfig', 'nested'),
                'nested' => [
                    'very' => new Path('value'),
                ],
            ],
        ];
        $preparedConfig = $this->service->replace($config);

        $this->assertSame(['very' => 'baz'], $preparedConfig['sharedConfig']['nesteded']);
    }

    public function testValueToValue()
    {
        $config = [
            'value' => 'baz',
            'sharedConfig' => [
                'nesteded' => new Path('sharedConfig', 'nested', 'very'),
                'nested' => [
                    'very' => new Path('value'),
                ],
            ],
        ];
        $preparedConfig = $this->service->replace($config);

        $this->assertSame('baz', $preparedConfig['sharedConfig']['nesteded']);
    }

    public function testFailPrepareConfigFromNonExisitigKey()
    {
        $config = [
            'bar' => 'foo',
            'awesome' => new Path('baz'),
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unknown configuration key baz');

        $this->service->replace($config);
    }

    public function testFailPrepareConfigFromNonExisitigNestedKey()
    {
        $config = [
            'bar' => 'foo',
            'awesome' => new Path('bar', 'baz'),
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unknown configuration key bar->baz');

        $this->service->replace($config);
    }

    public function testWorksWithNewInterface()
    {
        $config = [
            'path' => [
                'to' => 'first-element',
            ],
            'awesome' => Path::fromString('path/to'),
        ];

        $preparedConfig = $this->service->replace($config);

        $this->assertSame(['path' => ['to' => 'first-element'], 'awesome' => 'first-element'], $preparedConfig);
    }

    public function testValueLoop()
    {
        $config = [
            'awesome' => Path::fromString('awesome'),
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('cycle');

        $this->service->replace($config);
    }

    public function testValueLoop2()
    {
        $config = [
            'awesome' => Path::fromString('awesome2'),
            'awesome2' => Path::fromString('awesome3'),
            'awesome3' => Path::fromString('awesome'),
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('cycle');

        $this->service->replace($config);
    }
}
