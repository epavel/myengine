<?php

namespace MyEngine\Module;

use MyEngine\Event\AggregateInterface,
    MyEngine\Event\EventManager,
    MyEngine\Loader\Loader;

class DefaultListeners implements AggregateInterface
{
    private $loader;
	
    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Attach one or more listeners
     *
     * @param  EventManager $eventManager
     * @return DefaultListeners
     */
    public function attach(EventManager $eventManager)
    {
        $eventManager->attach(ModuleEvent::EVENT_LOAD_MODULE_RESOLVE, new ModuleResolverListener(), 100);
        $eventManager->attach(ModuleEvent::EVENT_LOAD_MODULE, new InitListener, 100);
        $eventManager->attach(ModuleEvent::EVENT_LOAD_MODULE, new AutoloaderListener($this->loader), 200);
        $eventManager->attachAggregate(new ConfigListener);
        return $this;
    }
}
