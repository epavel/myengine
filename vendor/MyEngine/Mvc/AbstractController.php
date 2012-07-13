<?php

namespace MyEngine\Mvc;

abstract class AbstractController
{
    /**
     * MVC event token
     * @var MvcEvent
     */
    protected $event;
	    
    public function indexAction()
    {
        return array();
    }
    
    /**
     * Get the MVC event instance
     *
     * @return MvcEvent
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Get the MVC event instance
     *
     * @param MvcEvent $event
     * @return AbstractController
     */
    public function setEvent(MvcEvent $event)
    {
        $this->event = $event;
        return $this;
    }

    public function getApplication()
    {
    	return $this->getEvent()->getApplication();
    }
    
    public function getEventManager()
    {
    	return $this->getApplication()->getEventManager();
    }
}
