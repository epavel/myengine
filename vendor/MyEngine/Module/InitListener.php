<?php

namespace MyEngine\Module;

class InitListener
{
    /**
     * @param ModuleEvent $e
     * @eturn void
     */
    public function __invoke(ModuleEvent $e)
    {
        $module = $e->getModule();
        if (method_exists($module, 'init')) {
            $module->init($e->getTarget());
        }
    }
}
