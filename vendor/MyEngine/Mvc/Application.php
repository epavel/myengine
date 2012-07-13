<?php

namespace MyEngine\Mvc;

use MyEngine\Event\EventManager;
use MyEngine\Module\ModuleManager;
use MyEngine\Mvc\MvcEvent;

class Application
{
    const ERROR_CONTROLLER_CANNOT_DISPATCH = 'error-controller-cannot-dispatch';
    const ERROR_CONTROLLER_NOT_FOUND       = 'error-controller-not-found';
    const ERROR_CONTROLLER_INVALID         = 'error-controller-invalid';
    const ERROR_EXCEPTION                  = 'error-exception';
    const ERROR_ROUTER_NO_MATCH            = 'error-router-no-match';

    /**
     * @var array
     */
    protected $config;

    /**
     * MVC event token
     * @var MvcEvent
     */
    protected $event;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var ModuleManager
     */
    protected $moduleManager;

    /**
     * @var EventManager
     */
    protected $eventManager;
    
    /**
     * Constructor
     *
     * @param mixed $configuration
     * @param ModuleManager $moduleManager 
     */
    public function __construct($config, ModuleManager $moduleManager)
    {
        $this->config  = array_merge_recursive($config, $moduleManager->getConfig());
    	$this->moduleManager = $moduleManager;
        $this->eventManager = $moduleManager->getEventManager();
        $this->request = new Request();
        $this->response = new Response();
        $this->router = RouteStack::factory($this->config['router']);
    }

    /**
     * Retrieve the application configuration
     * 
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Bootstrap the application
     *
     * Defines and binds the MvcEvent, and passes it the request, response, and 
     * router. Attaches the ViewManager as a listener. Triggers the bootstrap 
     * event.
     * 
     * @return Application
     */
    public function bootstrap()
    {    	
        $eventManager = $this->getEventManager();
        $eventManager->attachAggregate(new RouteListener());
        $eventManager->attachAggregate(new DispatchListener());
        $eventManager->attachAggregate(new ViewListener());

        // Setup MVC Event
        $this->event = $event = new MvcEvent();
        $event->setTarget($this);
        $event->setApplication($this)
              ->setRequest($this->getRequest())
              ->setResponse($this->getResponse())
              ->setRouter($this->router);

        // Trigger bootstrap events
        $eventManager->trigger('bootstrap', $event);
        return $this;
    }

    /**
     * Retrieve the module manager
     * 
     * @return ModuleManager
     */
    public function getModuleManager()
    {
        return $this->moduleManager;
    }

    /**
     * Get the request object
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get the response object
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
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
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManager
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * Run the application
     *
     * @triggers route(MvcEvent)
     *           Routes the request, and sets the RouteMatch object in the event.
     * @triggers dispatch(MvcEvent)
     *           Dispatches a request, using the discovered RouteMatch and
     *           provided request.
     * @triggers dispatch.error(MvcEvent)
     *           On errors (controller not found, action not supported, etc.),
     *           populates the event with information about the error type,
     *           discovered controller, and controller class (if known).
     *           Typically, a handler should return a populated Response object
     *           that can be returned immediately.
     * @return ResponseInterface
     */
    public function run()
    {
        $events = $this->getEventManager();
        $event  = $this->getEvent();

        // Define callback used to determine whether or not to short-circuit
        $callback = function ($result) use ($event) {        	
            if ($result instanceof Response) {
                return true;
            }
            if ($event->getError()) {
                return true;
            }
            return false;
        };

        // Trigger route event
        $result = $events->trigger('route', $event, $callback);
        
        if ($result instanceof Response) {
        	$event->setTarget($this);
            $events->trigger('finish', $event);
            return $result;
        }
        if ($event->getError()) {
        	return $this->completeRequest($event);
        }
        
        // Trigger dispatch event
        $result = $events->trigger('dispatch', $event, $callback);

        // Complete response
        if ($result instanceof Response) {
            $event->setTarget($this);
            $events->trigger('finish', $event);
            return $result;
        }
        $event->setResponse($this->getResponse());
        return $this->completeRequest($event);
    }

    /**
     * Complete the request
     *
     * Triggers "render" and "finish" events, and returns response from
     * event object.
     *
     * @param  MvcEvent $event
     * @return ResponseInterface
     */
    protected function completeRequest(MvcEvent $event)
    {
        $event->setTarget($this);
    	$eventManager = $this->getEventManager();
        $eventManager->trigger('render', $event);
        $eventManager->trigger('finish', $event);
        return $event->getResponse();
    }
}
