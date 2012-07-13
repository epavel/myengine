<?php

use MyEngine\Loader\Loader;
use MyEngine\Registry;
use MyEngine\Event\EventManager;
use MyEngine\Module\ModuleManager;
use MyEngine\Module\DefaultListeners;
use MyEngine\Mvc\Application;

chdir(dirname(__DIR__)); 

include 'vendor/MyEngine/Loader/Loader.php';

$loader = new Loader();
$loader->registerNamespaceFallbacks(array(
    'vendor',
    'modules'
));
$loader->register();

$config = include('config/config.php');

$eventManager = new EventManager();
$eventManager->attachAggregate(new DefaultListeners($loader));

$moduleManager = new ModuleManager($config['modules'], $eventManager);
$moduleManager->loadModules();

Registry::set('Application', $application = new Application($config, $moduleManager));
$response = $application->bootstrap()->run();
$response->send();

