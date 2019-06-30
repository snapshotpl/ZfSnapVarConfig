<?php

namespace ZfSnapVarConfig\Test\Value;

use PHPUnit\Framework\TestCase;
use ZfSnapVarConfig\Exception;
use ZfSnapVarConfig\Value\Callback;
use ZfSnapVarConfig\Value\Path;

class CallbackTest extends TestCase
{
    public function testAcceptCallback()
    {
        $config = [
            'ok' => true,
        ];

        $result = (new Callback(function(array $config): array {
            return $config;
        }))->value($config);

        $this->assertSame(['ok' => true], $result);
    }
}