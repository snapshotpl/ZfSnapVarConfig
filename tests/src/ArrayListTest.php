<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ZfSnapVarConfig\Test;

/**
 * ArrayListTest
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class ArrayListTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsInterface()
    {
        $input = array(
            'foo' => 'boo',
            'boo' => 'bar',
        );
        $arrayList = new \ZfSnapVarConfig\ArrayList($input);

        $this->assertInstanceOf('\ZfSnapVarConfig\VarConfigInterface', $arrayList);
    }

    public function testPropertyProcess()
    {
        $input = array(
            'foo' => 'boo',
            'boo' => 'bar',
        );
        $arrayList = new \ZfSnapVarConfig\ArrayList($input);
        $output = $arrayList->getNestedKeys();

        $this->assertEquals($input, $output);
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     * @expectedExceptionMessage array, string given
     */
    public function testKeysAllowsArrays()
    {
        $string = 'string';
        new \ZfSnapVarConfig\ArrayList($string);
    }
}
