<?php

namespace ZfSnapVarConfig;

use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManagerInterface;

/**
 * Module
 *
 * @author Witold Wasiczko <witold@wasiczko.pl>
 */
class Module implements InitProviderInterface
{

    /**
     * @param ModuleManagerInterface $manager
     */
    public function init(ModuleManagerInterface $manager)
    {
        $em = $manager->getEventManager();
        $em->attach(ModuleEvent::EVENT_MERGE_CONFIG, array($this, 'onMergeConfig'));
    }

    /**
     * @param ModuleEvent $event
     */
    public function onMergeConfig(ModuleEvent $event)
    {
        $configListener = $event->getConfigListener();
        $config = $configListener->getMergedConfig(false);

        $preparedConfig = $this->prepareConfig($config);

        $configListener->setMergedConfig($preparedConfig);
    }

    /**
     * @param array $config
     * @return array
     */
    public function prepareConfig(array $config)
    {
        array_walk_recursive($config, array($this, 'prepareConfigCallback'), $config);

        return $config;
    }

    /**
     * @param VarConfigInterface|mixed $item
     * @param string $itemKey
     * @param array $config
     * @return void
     * @throws Exception
     */
    public function prepareConfigCallback(&$item, $itemKey, $config)
    {
        if (!$item instanceof VarConfigInterface) {
            return;
        }
        $keys = $item->getNestedKeys();
        $prevKeys = array();
        $currentItem = $config;

        if (!is_array($keys) || count($keys) <= 0 ) {
            throw new Exception('It is not an array or is empty');
        }
        foreach ($keys as $key) {
            $prevKeys[] = $key;

            if (isset($currentItem[$key])) {
                $currentItem = $currentItem[$key];
            } else {
                throw new Exception(sprintf('Unknown configuration key %s', implode('->', $prevKeys)));
            }
        }
        if ($currentItem instanceof VarConfigInterface) {
            $this->prepareConfigCallback($currentItem, $itemKey, $config);
        }
        $item = $currentItem;
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
}
