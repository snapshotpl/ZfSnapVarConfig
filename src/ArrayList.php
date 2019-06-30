<?php

declare(strict_types=1);

namespace ZfSnapVarConfig;

/**
 * @deprecated Use ZfSnapVarConfig\Value interface
 */
final class ArrayList extends AbstractVarConfig
{
    /**
     * @param string[] $keys
     */
    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

}
