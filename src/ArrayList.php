<?php

namespace ZfSnapVarConfig;

/**
 * ArrayList
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class ArrayList extends AbstractVarConfig
{

    /**
     * @param array $keys
     */
    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

}
