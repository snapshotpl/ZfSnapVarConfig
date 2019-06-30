<?php

declare(strict_types=1);

namespace ZfSnapVarConfig;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManagerInterface;

final class Module implements InitProviderInterface
{
    public function init(ModuleManagerInterface $manager): void
    {
        $em = $manager->getEventManager();
        $em->attach(ModuleEvent::EVENT_MERGE_CONFIG, function (EventInterface $event): void {
            $this->onMergeConfigHandler($event);
        });
    }

    private function onMergeConfigHandler(ModuleEvent $event): void
    {
        $configListener = $event->getConfigListener();
        $config = $configListener->getMergedConfig(false);

        $preparedConfig = (new VarConfigService())->replace($config);

        $configListener->setMergedConfig($preparedConfig);
    }

    public function getConfig(): array
    {
        require __DIR__ .'/../config/module.config.php';
    }
}
