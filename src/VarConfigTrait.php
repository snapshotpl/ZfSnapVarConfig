<?php

namespace ZfSnapVarConfig;

trait VarConfigTrait
{
    /**
     * @var array
     */
    protected $keys;

    final public function getNestedKeys() : NestedKeys
    {
        return new NestedKeys($this->keys);
    }
}
