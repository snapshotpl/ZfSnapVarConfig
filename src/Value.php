<?php


namespace ZfSnapVarConfig;


interface Value
{
    /**
     * @return mixed
     */
    public function value(array $context);
}
