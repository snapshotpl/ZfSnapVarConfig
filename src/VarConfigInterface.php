<?php

declare(strict_types=1);

namespace ZfSnapVarConfig;

/**
 * @deprecated Use ZfSnapVarConfig\Value interface
 */
interface VarConfigInterface
{
    public function getNestedKeys() : NestedKeys;
}
