<?php

namespace ZfSnapVarConfig;

/**
 * VarConfigInterface
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
interface VarConfigInterface
{
    /**
     * @return array
     */
    public function getNestedKeys();
}
