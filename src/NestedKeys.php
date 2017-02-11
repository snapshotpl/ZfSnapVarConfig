<?php

declare(strict_types=1);

namespace ZfSnapVarConfig;

use Error;
use InvalidArgumentException;

final class NestedKeys
{
    private $keys;

    public function __construct(array $keys)
    {
        if (empty($keys)) {
            throw new InvalidArgumentException('Keys cannot be empty');
        }

        foreach ($keys as $key) {
            if (!is_scalar($key)) {
                throw new InvalidArgumentException('All keys must be scalar values');
            }
        }

        try {
            $this->keys = (function(...$keys){
                return $keys;
            })(...$keys);
        } catch (Error $exception) {
            throw new InvalidArgumentException('Cannot keys in array');
        }
    }

    public function getKeys() : array
    {
        return $this->keys;
    }
}
