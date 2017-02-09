<?php

namespace ZfSnapVarConfig;

use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManagerInterface;

final class Module implements InitProviderInterface
{
    public function init(ModuleManagerInterface $manager)
    {
        $em = $manager->getEventManager();
        $em->attach(ModuleEvent::EVENT_MERGE_CONFIG, [$this, 'onMergeConfig']);
    }

    public function onMergeConfig(ModuleEvent $event)
    {
        $configListener = $event->getConfigListener();
        $config = $configListener->getMergedConfig(false);

        $preparedConfig = $this->prepareConfig($config);

        $configListener->setMergedConfig($preparedConfig);
    }

    /**
     * @return array
     */
    public function prepareConfig(array $config)
    {
        array_walk_recursive($config, [$this, 'prepareConfigCallback'], $config);

        return $config;
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
        $item = $currentItem;
    }
}
