<?php


namespace ZfSnapVarConfig\Value;


use ZfSnapVarConfig\Value;

final class Callback implements Value
{
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return mixed
     */
    public function value(array $context)
    {
        return ($this->callback)($context);
    }
}