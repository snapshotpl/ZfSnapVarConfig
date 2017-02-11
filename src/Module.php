<?php

namespace ZfSnapVarConfig;

use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManagerInterface;

final class Module implements InitProviderInterface
{
    private $varConfigService;

    public function __construct()
    {
        $this->varConfigService = new VarConfigService();
    }

    public function init(ModuleManagerInterface $manager)
    {
        $manager->getEventManager()->attach(ModuleEvent::EVENT_MERGE_CONFIG, function ($event) {
            $this->onMergeConfigHandler($event);
        });
    }

    private function onMergeConfigHandler(ModuleEvent $event)
    {
        $configListener = $event->getConfigListener();
        $config = $configListener->getMergedConfig(false);

        $preparedConfig = $this->varConfigService->replace($config);

        $configListener->setMergedConfig($preparedConfig);
    }
}
