<?php

namespace ZfSnapVarConfig;

/**
 * ArrayList
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
final class ArrayList extends AbstractVarConfig
{

    /**
     * @param array $keys
     */
    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

}
