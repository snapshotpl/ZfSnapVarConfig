<?php

namespace ZfSnapVarConfig\Test;

use PHPUnit_Framework_TestCase;
use ZfSnapVarConfig\ArrayList;

class ArrayListTest extends PHPUnit_Framework_TestCase
{
    public function testImplementsInterface()
    {
        $input = array(
            'foo' => 'boo',
            'boo' => 'bar',
        );
        $arrayList = new ArrayList($input);

        $this->assertInstanceOf('\ZfSnapVarConfig\VarConfigInterface', $arrayList);
    }

    public function testPropertyProcess()
    {
        $input = array(
            'foo' => 'boo',
            'boo' => 'bar',
        );
        $arrayList = new ArrayList($input);
        $output = $arrayList->getNestedKeys();

        $this->assertEquals($input, $output);
    }
}
