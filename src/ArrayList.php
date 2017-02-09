<?php

namespace ZfSnapVarConfig;

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
