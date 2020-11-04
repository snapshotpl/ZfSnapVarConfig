<?php

namespace ZfSnapVarConfig\Test\Value;

use PHPUnit\Framework\TestCase;
use ZfSnapVarConfig\Exception;
use ZfSnapVarConfig\Value\Callback;
use ZfSnapVarConfig\Value\Env;
use ZfSnapVarConfig\Value\Path;

class EnvTest extends TestCase
{
    protected function setUp():void
    {
        putenv(self::class);
    }

    protected function tearDown(): void
    {
        putenv(self::class);
    }

    public function testGetEnv()
    {
        putenv(self::class.'=value');

        $result = (new Env(self::class))->value([]);

        $this->assertSame('value', $result);
    }

    public function testEnvNotExist()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Missing ZfSnapVarConfig\Test\Value\EnvTest env');

        (new Env(self::class))->value([]);
    }
}
