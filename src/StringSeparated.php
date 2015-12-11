<?php

namespace ZfSnapVarConfig;

/**
 * StringSeparated
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
final class StringSeparated extends AbstractVarConfig
{
    const DEFAULT_SEPARATOR = '.';

    /**
     * @param string $string
     * @param string $separator
     * @throws Exception
     */
    public function __construct($string, $separator = self::DEFAULT_SEPARATOR)
    {
        if (!is_string($string)) {
            throw new Exception('Expect first parameter to be string');
        }
        if (!is_string($separator)) {
            throw new Exception('Expect second parameter to be string');
        }
        $this->keys = explode($separator, $string);
    }

}
