<?php


namespace ZfSnapVarConfig\Value;


use ZfSnapVarConfig\Exception;
use ZfSnapVarConfig\Value;

final class Path implements Value
{
    private $path;

    public function __construct(...$path)
    {
        if (empty($path)) {
            throw new Exception('Path cannot be empty');
        }
        $this->path = $path;
    }

    public static function fromArray(array $path): self
    {
        return new self(... $path);
    }

    public static function fromString(string $path, string $separator = '/'): self
    {
        if ($path === '') {
            throw new Exception('Path cannot be empty');
        }

        if ($separator === '') {
            throw new Exception('Separator cannot be empty');
        }

        return new self(... explode($separator, $path));
    }

    /**
     * @return mixed
     */
    public function value(array $context)
    {
        $currentItem = $context;
        $prevKeys = [];

        foreach ($this->path as $key) {
            $prevKeys[] = $key;

            if (! is_string($key) && !is_int($key)) {
                throw new Exception(sprintf('Key can be only string or int in %s', implode('->', $prevKeys)));
            }

            if (!is_array($currentItem) || ! array_key_exists($key, $currentItem)) {
                throw new Exception(sprintf('Unknown configuration key %s', implode('->', $prevKeys)));
            }
            $currentItem = $currentItem[$key];
        }
        return $currentItem;
    }
}
