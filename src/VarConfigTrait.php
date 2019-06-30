<?php

namespace ZfSnapVarConfig;

/**
 * @deprecated Use ZfSnapVarConfig\Value interface
 */
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
