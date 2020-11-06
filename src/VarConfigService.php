<?php

declare(strict_types=1);

namespace ZfSnapVarConfig;

final class VarConfigService
{
    private $values = [];
    private $cache = [];

    public function replace(array $data) : array
    {
        return $this($data);
    }

    public function __invoke(array $data) : array
    {
        $this->values = [];
        $this->cache = [];
        array_walk_recursive($data, [$this, 'prepareConfigCallback'], $data);

        return $data;
    }

    /**
     * @param Value|mixed $item
     *
     * @throws Exception
     */
    private function prepareConfigCallback(&$item, string $itemKey, array $config): void
    {
        $currentItem = $item;
        $hash = null;

        if (! $currentItem instanceof Value) {
            return;
        }

        if ($hash === null) {
            $hash = spl_object_hash($currentItem);
        }

        if (isset($this->cache[$hash])) {
            $item = $this->cache[$hash];
            return;
        }

        if (isset($this->values[$hash])) {
            throw new Exception('A cycle was detected for '. $itemKey);
        }

        $this->values[$hash] = $currentItem;

        $currentItem = $currentItem->value($config);

        if ($currentItem instanceof Value) {
            $this->prepareConfigCallback($currentItem, $itemKey, $config);
        }

        $this->cache[$hash] = $currentItem;

        if (is_array($currentItem)) {
            array_walk_recursive($currentItem, [$this, 'prepareConfigCallback'], $config);
        }

        $item = $currentItem;
    }
}
