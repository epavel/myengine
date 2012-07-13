<?php

namespace MyEngine\Module;

use MyEngine\Event\AggregateInterface,
    MyEngine\Event\EventManager; 
	
class ConfigListener implements ConfigListenerInterface, AggregateInterface
{
    protected $config = Array();
	
    public function attach(EventManager $events)
    {
        $this->listeners[] = $events->attach(ModuleEvent::EVENT_LOAD_MODULE, array($this, 'loadModule'), 100);
        $this->listeners[] = $events->attach(ModuleEvent::EVENT_LOAD_MODULES_PRE, array($this, 'loadModulesPre'), 900);
        return $this;
    }

    /**
     * Pass self to the ModuleEvent object early so everyone has access.
     *
     * @param  ModuleEvent $e
     * @return ConfigListener
     */
    public function loadModulesPre(ModuleEvent $e)
    {
        $e->setConfigListener($this);
        return $this;
    }

    /**
     * Merge the config for each module
     *
     * @param  ModuleEvent $e
     * @return ConfigListener
     */
    public function loadModule(ModuleEvent $e)
    {
        $module = $e->getModule();
        if (is_callable(array($module, 'getConfig'))) {
            $this->config = array_merge_recursive($this->config, $module->getConfig());
        }
        return $this;
    }

    /**
     * getConfig
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * setConfig
     *
     * @param  array $config
     * @return ConfigListener
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }
}
