<?php

namespace MyEngine\Module;

use MyEngine\Event\Event;

class ModuleEvent extends Event
{
	const EVENT_LOAD_MODULE = 'loadModule';
	const EVENT_LOAD_MODULE_RESOLVE = 'loadModule.resolve';
	const EVENT_LOAD_MODULES_PRE = 'loadModules.pre';
	const EVENT_LOAD_MODULES_POST = 'loadModules.post';
	
	
    protected $configListener;
	
    protected $moduleName;
	
    protected $module;

    /**
     * Get the name of a given module
     *
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * Set the name of a given module
     *
     * @param  string $moduleName
     * @return ModuleEvent
     */
    public function setModuleName($moduleName)
    {
        if (!is_string($moduleName)) {
            throw new \InvalidArgumentException(sprintf(
                '%s expects a string as an argument; %s provided'
                ,__METHOD__, gettype($moduleName)
            ));
        }
        $this->moduleName = $moduleName;
        return $this;
    }

    /**
     * Get module object
     *
     * @return null|object
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set module object to compose in this event
     *
     * @param  object $module
     * @return ModuleEvent
     */
    public function setModule($module)
    {
        if (!is_object($module)) {
            throw new \InvalidArgumentException(sprintf(
                '%s expects a module object as an argument; %s provided'
                ,__METHOD__, gettype($module)
            ));
        }
        $this->module = $module;
        return $this;
    }
    
    
    /**
     * Set configListener object to compose in this event
     *
     * @param  ConfigListenerInterface $module
     * @return ModuleEvent
     */
    public function setConfigListener(ConfigListenerInterface $configListener)
    {
    	$this->configListener = $configListener;
    	return $this;
    }
    
    /**
     * @return ConfigListenerInterface
     */    
    public function getConfigListener()
    {
    	return $this->configListener;
    }    
}
