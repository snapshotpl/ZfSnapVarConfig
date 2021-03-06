<?php

namespace ZfSnapVarConfig\Test;

use PHPUnit\Framework\TestCase;
use ZfSnapVarConfig\ArrayList;
use ZfSnapVarConfig\VarConfigInterface;

class ArrayListTest extends TestCase
{
    public function testImplementsInterface()
    {
        $input = [
            'foo' => 'boo',
            'boo' => 'bar',
        ];
        $arrayList = new ArrayList($input);

        $this->assertInstanceOf(VarConfigInterface::class, $arrayList);
    }

    public function testPropertyProcess()
    {
        $input = [
            'boo',
            'bar',
        ];
        $arrayList = new ArrayList($input);
        $nestedKeys = $arrayList->getNestedKeys();

        $this->assertSame($input, $nestedKeys->getKeys());
    }
}
