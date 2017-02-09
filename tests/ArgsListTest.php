<?php

namespace ZfSnapVarConfig\Test;

use ZfSnapVarConfig\ArgsList;

class ArgsListTest extends \PHPUnit_Framework_TestCase
{
    public function testPropertyProcess()
    {
        $arrayList = new ArgsList('boo', 'foo');
        $output = $arrayList->getNestedKeys();

        $this->assertSame(['boo', 'foo'], $output);
    }
}
