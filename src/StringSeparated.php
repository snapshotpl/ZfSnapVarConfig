<?php

declare(strict_types=1);

namespace ZfSnapVarConfig;

/**
 * @deprecated Use ZfSnapVarConfig\Value interface
 */
final class StringSeparated extends AbstractVarConfig
{
    const DEFAULT_SEPARATOR = '.';

    /**
     * @throws Exception
     */
    public function __construct(string $string, string $separator = self::DEFAULT_SEPARATOR)
    {
        if (empty($string)) {
            throw new InvalidArgumentException('String cannot be empty');
        }
        if (empty($separator)) {
            throw new InvalidArgumentException('Separator cannot be empty');
        }
        $this->keys = explode($separator, $string);
    }

}
