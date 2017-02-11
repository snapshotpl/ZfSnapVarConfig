<?php

declare(strict_types=1);

namespace ZfSnapVarConfig;

interface VarConfigInterface
{
    public function getNestedKeys() : NestedKeys;
}
