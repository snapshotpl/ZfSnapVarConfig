<?php

namespace ZfSnapVarConfig;

abstract class AbstractVarConfig implements VarConfigInterface
{
    /**
     * @var array
     */
    protected $keys;

    /**
     * @return array
     */
    final public function getNestedKeys()
    {
        return (array) $this->keys;
    }
}
