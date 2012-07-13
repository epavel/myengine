<?php

namespace MyEngine\Module;

class ModuleResolverListener
{
    /**
     * @param  ModuleEvent $e
     * @return object
     */
    public function __invoke(ModuleEvent $e)
    {
        $moduleName = $e->getModuleName();
        $class      = $moduleName . '\Module';

        if (!class_exists($class)) {
            return false;
        }

        return new $class;
    }
}
