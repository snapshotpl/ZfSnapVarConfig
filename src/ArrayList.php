<?php

declare(strict_types=1);

namespace ZfSnapVarConfig;

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
