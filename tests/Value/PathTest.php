<?php

namespace ZfSnapVarConfig\Test\Value;

use PHPUnit\Framework\TestCase;
use ZfSnapVarConfig\Exception;
use ZfSnapVarConfig\Value\Path;

class PathTest extends TestCase
{
    public function testPrepareConfigFromFirstElement()
    {
        $config = [
            'sharedConfig' => 'sharedValue',
        ];

        $result = (new Path('sharedConfig'))->value($config);

        $this->assertSame('sharedValue', $result);
    }

    public function testPrepareConfigFromNullValue()
    {
        $config = [
            'sharedConfig' => null,
        ];

        $result = (new Path('sharedConfig'))->value($config);

        $this->assertNull($result);
    }

    public function testPrepareConfigFromNestedKeys()
    {
        $config = [
            'sharedConfig' => [
                'nested' => [
                    'very' => 'nestedValue',
                ],
            ],
        ];
        $result = (new Path('sharedConfig', 'nested', 'very'))->value($config);

        $this->assertSame('nestedValue', $result);
    }

    public function testPrepareConfigFromArray()
    {
        $config = [
            'sharedConfig' => [
                'nested' => [
                    'very' => 'nestedValue',
                ],
            ],
        ];
        $result = Path::fromArray(['sharedConfig', 'nested', 'very'])->value($config);

        $this->assertSame('nestedValue', $result);
    }

    public function testPrepareConfigFromEmptyArray()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('empty');

        Path::fromArray([]);
    }

    public function testPrepareConfigFromStringPath()
    {
        $config = [
            'sharedConfig' => [
                'nested' => [
                    'very' => 'nestedValue',
                ],
            ],
        ];
        $result = Path::fromString('sharedConfig/nested/very')->value($config);

        $this->assertSame('nestedValue', $result);
    }

    public function testPrepareConfigFromEmptyStringPath()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('empty');

        Path::fromString('');
    }

    public function testPrepareConfigFromStringPathAndEmptySeparator()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('empty');

        Path::fromString('sharedConfig/nested/very', '');
    }

    public function testPrepareConfigFromStringPathWithCustomSeparator()
    {
        $config = [
            'sharedConfig' => [
                'nested' => [
                    'very' => 'nestedValue',
                ],
            ],
        ];
        $result = Path::fromString('sharedConfig.nested.very', '.')->value($config);

        $this->assertSame('nestedValue', $result);
    }

//    public function testPrepareConfigFromNestedVars()
//    {
//        $config = [
//            'value' => 'baz',
//            'sharedConfig' => [
//                'nested' => [
//                    'very' => Path::argList('value'),
//                ],
//            ],
//        ];
//        $result = Path::argList('sharedConfig', 'nested', 'very')->value($config);
//
//        $this->assertSame('baz', $result);
//    }

//    public function testPrepareConfigFromNestedVars2()
//    {
//        $config = [
//            'value' => 'baz',
//            'sharedConfig' => [
//                'nesteded' => Path::argList(),
//                'nested' => [
//                    'very' => Path::argList('value'),
//                ],
//            ],
//        ];
//        $result = Path::argList('sharedConfig', 'nested')->value($config);
//
//        $this->assertSame(['very' => 'baz'], $result);
//    }

    public function testFailPrepareConfigFromNonExisitigKey()
    {
        $config = [
            'bar' => 'foo',
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unknown configuration key baz');

        (new Path('baz'))->value($config);
    }

    public function testFailPrepareConfigFromNonExisitigNestedKey()
    {
        $config = [
            'bar' => 'foo',
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unknown configuration key bar->baz');

        (new Path('bar', 'baz'))->value($config);
    }

    public function testWorksWithNumeric()
    {
        $config = [
            'bar' => [
                'first-element',
            ],
        ];

        $result = (new Path('bar', 0))->value($config);

        $this->assertSame('first-element', $result);
    }

    public function testDoesntWorksWithFloats()
    {
        $config = [
            0.5 => 'first-element',
        ];

        $this->expectException(Exception::class);

        (new Path(0.5))->value($config);
    }
}