<?php

namespace ZfSnapVarConfig;

final class ArgsList extends AbstractVarConfig
{
    public function __construct(...$keys)
    {
        $this->keys = $keys;
    }
}
