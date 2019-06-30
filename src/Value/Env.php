<?php


namespace ZfSnapVarConfig\Value;


use ZfSnapVarConfig\Exception;
use ZfSnapVarConfig\Value;

final class Env implements Value
{
    private $envName;

    public function __construct(string $envName)
    {
        $this->envName = $envName;
    }

    /**
     * @return mixed
     */
    public function value(array $context)
    {
        $result = getenv($this->envName);

        if ($result === false) {
            throw new Exception('Missing '. $this->envName .' env');
        }

        return $result;
    }
}