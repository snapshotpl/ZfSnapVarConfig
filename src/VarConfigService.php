<?php

namespace ZfSnapVarConfig;

final class VarConfigService
{
    public function replace(array $data)
    {
        return $this($data);
    }

    public function __invoke(array $data)
    {
        array_walk_recursive($data, [$this, 'prepareConfigCallback'], $data);

        return $data;
    }

    /**
     * @param VarConfigInterface|mixed $item
     * @param string $itemKey
     * @param array $config
     *
     * @return void
     *
     * @throws Exception
     */
    public function prepareConfigCallback(&$item, $itemKey, array $config)
    {
        if (!$item instanceof VarConfigInterface) {
            return;
        }
        $keys = $item->getNestedKeys();
        $prevKeys = [];
        $currentItem = $config;

        if (!is_array($keys) || empty($keys)) {
            throw new Exception('It is not an array or is empty');
        }

        foreach ($keys as $key) {
            $prevKeys[] = $key;

            if (!isset($currentItem[$key])) {
                throw new Exception(sprintf('Unknown configuration key %s', implode('->', $prevKeys)));
            }
            $currentItem = $currentItem[$key];
        }

        if ($currentItem instanceof VarConfigInterface) {
            $this->prepareConfigCallback($currentItem, $itemKey, $config);
        }

        if (is_array($currentItem)) {
            array_walk_recursive($currentItem, [$this, 'prepareConfigCallback'], $config);
        }

        $item = $currentItem;
    }
}
