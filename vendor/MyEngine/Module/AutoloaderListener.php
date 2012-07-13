<?php

namespace MyEngine\Module;

use MyEngine\Loader\Loader;

class AutoloaderListener
{
    private $loader;
	
    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param  ModuleEvent $e
     * @return void
     */
    public function __invoke(ModuleEvent $e)
    {
        $module = $e->getModule();
        if (method_exists($module, 'getAutoloaderConfig')) {
            $this->loader->registerNamespaces($module->getAutoloaderConfig());
        }
    }
}
