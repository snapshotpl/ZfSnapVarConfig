<?php

namespace ZfSnapVarConfig\Test;

use PHPUnit\Framework\TestCase;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\Listener\ConfigListener;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;
use ZfSnapVarConfig\ArgsList;
use ZfSnapVarConfig\Module;

class ModuleTest extends TestCase
{
    private $module;

    protected function setUp()
    {
        $this->module = new Module();
    }

    public function testMergeConfig()
    {
        $eventManager = new EventManager();
        $configListener = new ConfigListener();
        $configListener->setMergedConfig([
            'value' => 1,
            'copied' => new ArgsList('value'),
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
