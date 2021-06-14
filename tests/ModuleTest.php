<?php

namespace ZfSnapVarConfig\Test;

use PHPUnit\Framework\TestCase;
use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManager;
use Laminas\EventManager\EventManagerInterface;
use Laminas\ModuleManager\Listener\ConfigListener;
use Laminas\ModuleManager\ModuleEvent;
use Laminas\ModuleManager\ModuleManager;
use ZfSnapVarConfig\ArgsList;
use ZfSnapVarConfig\Module;
use ZfSnapVarConfig\Value\Path;

class ModuleTest extends TestCase
{
    private $module;

    protected function setUp(): void
    {
        $this->module = new Module();
    }

    public function testMergeConfig()
    {
        $eventManager = new EventManager();
        $configListener = new ConfigListener();
        $configListener->setMergedConfig([
            'value' => 1,
            'copied' => new Path('value'),
        ]);

        $moduleManager = new ModuleManager([], $eventManager);

        $event = $moduleManager->getEvent();
        $event->setName(ModuleEvent::EVENT_MERGE_CONFIG);
        $event->setConfigListener($configListener);

        $this->module->init($moduleManager);

        $this->triggerEvent($eventManager, $event);

        $result = $configListener->getMergedConfig(false);

        $this->assertSame(['value' => 1, 'copied' => 1], $result);
    }

    private function triggerEvent(EventManagerInterface $eventManager, EventInterface $event)
    {
        if (method_exists($eventManager, 'triggerEvent')) {
            $eventManager->triggerEvent($event);
        } else {
            $eventManager->trigger($event);
        }
    }
}
