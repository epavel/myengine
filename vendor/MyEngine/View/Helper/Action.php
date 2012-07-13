<?php

namespace MyEngine\View\Helper;

use MyEngine\Registry;
use MyEngine\Mvc\MvcEvent;

class Action extends AbstractHelper
{
    public function __invoke($action, $controller)
    {
        $application = Registry::get('Application');        
        $mvcEvent = $application->getEvent();        
        $mvcEvent->getRouteMatch()
            ->setParam('controller', $controller)
            ->setParam('action', $action);
            
        $application->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH, $mvcEvent);         
        
        return $this->getView()
            ->render($mvcEvent->getResult());
    }
}
