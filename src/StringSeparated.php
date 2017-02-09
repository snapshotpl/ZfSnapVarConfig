<?php

namespace ZfSnapVarConfig;

final class StringSeparated extends AbstractVarConfig
{
    const DEFAULT_SEPARATOR = '.';

    /**
     * @param string $string
     * @param string $separator
     *
     * @throws Exception
     */
    public function __construct($string, $separator = self::DEFAULT_SEPARATOR)
    {
        if (!is_string($string) || empty($string)) {
            throw new Exception('Expect first parameter to be not empty string');
        }
        if (!is_string($separator) || empty($separator)) {
            throw new Exception('Expect second parameter to be not empty string');
        }
        $this->keys = explode($separator, $string);
    }

}
