<?php

namespace ZfSnapVarConfig;

trait VarConfigTrait
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
