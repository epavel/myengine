<?php

namespace MyEngine\Mvc;

use MyEngine\Event\EventManager;
use MyEngine\Event\AggregateInterface;

class DispatchListener implements AggregateInterface
{
    /**
     * Attach listeners to an event manager
     *
     * @param  EventManager $eventManager 
     * @return void
     */
    public function attach(EventManager $eventManager)
    {
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'));
    }

    /**
     * Listen to the "dispatch" event
     * 
     * @param  MvcEvent $e 
     * @return mixed
     */
    public function onDispatch(MvcEvent $e)
    {    	 
    	$routeMatch       = $e->getRouteMatch();
        $controllerName   = $routeMatch->getParam('controller', 'not-found');
        $application      = $e->getApplication();
        $events           = $application->getEventManager();
        
        /**
         * TO-DO: controller resolve event 
         */
        
        $config = $application->getConfig();
        
        $exception = false;
        try {            
            if(!$config['controller']['classes'][$controllerName]){
                throw new \Exception('123');
            }
        	$className = '\\' . $config['controller']['classes'][$controllerName];
        	$controller = new $className;
        } catch (\Exception $exception) {
            $e->setError($application::ERROR_CONTROLLER_NOT_FOUND)
                  ->setController($controllerName)
                  ->setException($exception);
            $return = $events->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $e);
            if (! $return) {
                $return = $e->getResult();
            }
            return $this->complete($return, $e);
        }

        $controller->setEvent($e);

        try {
        	// execute action
        	$actionName = self::getActionMethodName($routeMatch->getParam('action'));
            $return = $controller->$actionName();
        } catch (\Exception $exception) {
            $e->setError($application::ERROR_EXCEPTION)
                  ->setController($controllerName)
                  ->setControllerClass(get_class($controller))
                  ->setException($exception);
            $return = $events->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $e);
            if (! $return) {
                $return = $e->getResult();
            }
        }
        return $this->complete($return, $e);
    }

    /**
     * Complete the dispatch
     * 
     * @param  mixed $return 
     * @param  MvcEvent $event 
     * @return mixed
     */
    protected function complete($return, MvcEvent $event)
    {
        $event->setResult($return);
        return $return;
    }
    
    public static function getActionMethodName($action)
    {
        $method  = str_replace(array('.', '-', '_'), ' ', $action);
        $method  = ucwords($method);
        $method  = str_replace(' ', '', $method);
        $method  = lcfirst($method);
        $method .= 'Action';
        return $method;
    }    
}
