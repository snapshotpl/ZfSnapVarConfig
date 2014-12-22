<?php

namespace ZfSnapVarConfig;

/**
 * AbstractVarConfig
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
abstract class AbstractVarConfig implements VarConfigInterface
{
    /**
     * @var array
     */
    protected $keys;

    /**
     * @return array
     */
    public function getNestedKeys()
    {
        return (array) $this->keys;
    }
}
