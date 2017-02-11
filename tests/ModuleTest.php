<?php

namespace ZfSnapVarConfig\Test;

use PHPUnit\Framework\TestCase;
use Zend\EventManager\EventManager;
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

        $eventManager->triggerEvent($event);

        $result = $configListener->getMergedConfig(false);

        $this->assertSame(['value' => 1, 'copied' => 1], $result);
    }
}
