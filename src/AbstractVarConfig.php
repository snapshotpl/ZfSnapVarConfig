<?php

declare(strict_types=1);

namespace ZfSnapVarConfig;

/**
 * @deprecated Use ZfSnapVarConfig\Value interface
 */
abstract class AbstractVarConfig implements VarConfigInterface
{
    use VarConfigTrait;
}
