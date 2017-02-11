<?php

namespace ZfSnapVarConfig\Test;

use PHPUnit\Framework\TestCase;
use ZfSnapVarConfig\ArgsList;

class ArgsListTest extends TestCase
{
    public function testPropertyProcess()
    {
        $arrayList = new ArgsList('boo', 'foo');
        $nestedKeys = $arrayList->getNestedKeys();

        $this->assertSame(['boo', 'foo'], $nestedKeys->getKeys());
    }
}
