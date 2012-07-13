<?php

namespace Core;

use MyEngine\Module\ModuleManager;

class Module 
{
	public function init(ModuleManager $moduleManager) 
	{}
	
    public function getAutoloaderConfig()
    {
    	return array(
    		 __NAMESPACE__ => __DIR__ . '/src', 
    	);
    }
    
    public function getConfig()
    {
        return include(__DIR__ . '/config/config.php');
    }
}
