<?php

declare(strict_types=1);

namespace ZfSnapVarConfig;

/**
 * @deprecated Use ZfSnapVarConfig\Value interface
 */
final class ArgsList extends AbstractVarConfig
{
    public function __construct(...$keys)
    {
        $this->keys = $keys;
    }
}
