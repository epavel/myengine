<?php

namespace MyEngine\Module;

use MyEngine\Event\EventManager;

class ModuleManager
{
    /**
     * @var array An array of Module classes of loaded modules
     */
    protected $loadedModules = array();

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var ModuleEvent
     */
    protected $event;

    /**
     * modules
     *
     * @var array
     */
    protected $modules = array();

    /**
     * True if modules have already been loaded
     *
     * @var bool
     */
    protected $isLoaded = false;

    /**
     * Constructor
     *
     * @param  array $modules
     * @param  EventManager $eventManager
     * @return void
     */
    public function __construct($modules, EventManager $eventManager = null)
    {
        $this->setModules($modules);
        $this->setEventManager($eventManager);
    }

    /**
     * Load the provided modules.
     *
     * @triggers loadModules.pre
     * @triggers loadModules.post
     * @return   ModuleManager
     */
    public function loadModules()
    {
        if (true === $this->isLoaded) {
            return $this;
        }

        $this->getEventManager()->trigger(ModuleEvent::EVENT_LOAD_MODULES_PRE, $this->getEvent());

        foreach ($this->getModules() as $moduleName) {
            $this->loadModule($moduleName);
        }

        $this->getEventManager()->trigger(ModuleEvent::EVENT_LOAD_MODULES_POST, $this->getEvent());

        $this->isLoaded = true;
        return $this;
    }

    /**
     * Load a specific module by name.
     *
     * @param    string $moduleName
     * @triggers loadModule.resolve
     * @triggers loadModule
     * @return   mixed Module's Module class
     */
    public function loadModule($moduleName)
    {
        if (isset($this->loadedModules[$moduleName])) {
            return $this->loadedModules[$moduleName];
        }

        $event = $this->getEvent();
        $event->setModuleName($moduleName);
        $module = $this->getEventManager()->trigger(ModuleEvent::EVENT_LOAD_MODULE_RESOLVE, $event);
		
        if (!is_object($module)) {
            throw new \RuntimeException(sprintf(
                'Module (%s) could not be initialized.',
                $moduleName
            ));
        }
        
        $event->setModule($module);
        $this->getEventManager()->trigger(ModuleEvent::EVENT_LOAD_MODULE, $event);
        $this->loadedModules[$moduleName] = $module;
        return $module;
    }

    /**
     * Get an array of the loaded modules.
     *
     * @param  bool $loadModules If true, load modules if they're not already
     * @return array An array of Module objects, keyed by module name
     */
    public function getLoadedModules($loadModules = false)
    {
        if (true === $loadModules) {
            $this->loadModules();
        }
        return $this->loadedModules;
    }

    /**
     * Get an instance of a module class by the module name 
     * 
     * @param  string $moduleName 
     * @return mixed
     */
    public function getModule($moduleName)
    {
        if (!isset($this->loadedModules[$moduleName])) {
            return null;
        }
        return $this->loadedModules[$moduleName];
    }

    /**
     * Get the array of module names that this manager should load.
     *
     * @return array
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * Set an array or Traversable of module names that this module manager should load.
     *
     * @param  mixed $modules array or Traversable of module names
     * @return ModuleManager
     */
    public function setModules($modules)
    {
        if (is_array($modules)) {
            $this->modules = $modules;
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Parameter to %s\'s %s method must be an array',
                __CLASS__, __METHOD__
            ));
        }
        return $this;
    }

    /**
     * Get the module event
     *
     * @return ModuleEvent
     */
    public function getEvent()
    {
        if (!$this->event instanceof ModuleEvent) {
            $this->setEvent(new ModuleEvent);
        }
        return $this->event;
    }

    /**
     * Set the module event
     *
     * @param  ModuleEvent $event
     * @return ModuleManager
     */
    public function setEvent(ModuleEvent $event)
    {
		$event->setTarget($this);
        $this->event = $event;
        return $this;
    }

    /**
     * Set the event manager instance used by this module manager.
     *
     * @param  EventManagerInterface $events
     * @return ModuleManager
     */
    public function setEventManager(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManager
     */
    public function getEventManager()
    {
        if (!$this->eventManager instanceof EventManager) {
            $this->setEventManager(new EventManager());
        }
        return $this->eventManager;
    }

    /**
     * @return ConfigListenerInterface
     */
    public function getConfigListener()
    {
    	return $this->getEvent()->getConfigListener();
    }
    
    
    /**
     * @return Array
     */
    public function getConfig()
    {
    	return $this->getConfigListener()->getConfig();
    }
}
