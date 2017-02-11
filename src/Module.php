<?php

declare(strict_types=1);

namespace ZfSnapVarConfig;

use Zend\EventManager\EventInterface;
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
        $em = $manager->getEventManager();
        $em->attach(ModuleEvent::EVENT_MERGE_CONFIG, function (EventInterface $event) {
            $this->onMergeConfigHandler($event);
        });
        $em->attach(ModuleEvent::EVENT_LOAD_MODULES_POST, function (EventInterface $event){
            $sm = $event->getParam('ServiceManager');
            if ($sm !== null) {
                $sm->setService(VarConfigService::class, $this->varConfigService);
            }
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
