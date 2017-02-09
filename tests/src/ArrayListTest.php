<?php

namespace ZfSnapVarConfig\Test;

use PHPUnit_Framework_TestCase;
use ZfSnapVarConfig\ArrayList;
use ZfSnapVarConfig\VarConfigInterface;

class ArrayListTest extends PHPUnit_Framework_TestCase
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
            'foo' => 'boo',
            'boo' => 'bar',
        ];
        $arrayList = new ArrayList($input);
        $output = $arrayList->getNestedKeys();

        $this->assertSame($input, $output);
    }
}
