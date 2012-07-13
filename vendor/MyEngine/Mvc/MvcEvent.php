<?php

namespace MyEngine\Mvc;

use MyEngine\Event\Event;
use MyEngine\View\ModelInterface;
use MyEngine\View\ViewModel;

class MvcEvent extends Event
{
    const EVENT_BOOTSTRAP      = 'bootstrap';
    const EVENT_DISPATCH       = 'dispatch';
    const EVENT_DISPATCH_ERROR = 'dispatch.error';
    const EVENT_FINISH         = 'finish';
    const EVENT_RENDER         = 'render';
    const EVENT_ROUTE          = 'route';

    protected $application;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var mixed
     */
    protected $result;

    /**
     * @var RouteStack
     */
    protected $router;

    /**
     * @var RouteMatch
     */
    protected $routeMatch;

    /**
     * @var string
     */
    protected $controller;
    
    /**
     * @var string
     */
    protected $controllerClass;

    /**
     * @var string
     */
    protected $error;

    /**
     * @var \Exception;
     */
    protected $exception;    
    
    /**
     * Set the view model
     *
     * @param  Model $viewModel
     * @return MvcEvent
     */
    public function setViewModel(ModelInterface $viewModel)
    {
        $this->viewModel = $viewModel;
        return $this;
    }

    /**
     * Get the view model
     *
     * @return Model
     */
    public function getViewModel()
    {
        if (null === $this->viewModel) {
            $this->setViewModel(new ViewModel());
        }
        return $this->viewModel;
    }
        
    /**
     * Set application instance
     * 
     * @param  Application $application 
     * @return MvcEvent
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
        return $this;
    }

    /**
     * Get application instance
     * 
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Get router
     *
     * @return Router\RouteStack
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Set router
     *
     * @param RouteStack $router
     * @return MvcEvent
     */
    public function setRouter(RouteStack $router)
    {
        $this->router = $router;
        return $this;
    }

    /**
     * Get route match
     *
     * @return RouteMatch
     */
    public function getRouteMatch()
    {
        return $this->routeMatch;
    }

    /**
     * Set route match
     *
     * @param RouteMatch $matches
     * @return MvcEvent
     */
    public function setRouteMatch(RouteMatch $matches)
    {
        $this->routeMatch = $matches;
        return $this;
    }

    /**
     * Get request
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set request
     *
     * @param Request $request
     * @return MvcEvent
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Get response
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set response
     *
     * @param Response $response
     * @return MvcEvent
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Get result
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set result
     *
     * @param mixed $result
     * @return MvcEvent
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Does the event represent an error response?
     * 
     * @return bool
     */
    public function isError()
    {
        return $this->error;
    }

    /**
     * Set the error message (indicating error in handling request)
     * 
     * @param  string $message 
     * @return MvcEvent
     */
    public function setError($message)
    {
        $this->error = $message;
        return $this;
    }

    /**
     * Retrieve the error message, if any
     * 
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Get the currently registered controller name
     * 
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set controller name
     *
     * @param  string $name
     * @return MvcEvent
     */
    public function setController($name)
    {
        $this->controller = $name;
        return $this;
    }

    /**
     * Get controller clas
     *
     * @return string
     */
    public function getControllerClass()
    {
        return $this->controllerClass;
    }

    /**
     * Set controller class
     *
     * @param string $class
     * @return MvcEvent
     */
    public function setControllerClass($class)
    {
        $this->controllerClass = $class;
        return $this;
    }
    
    /**
     * Get controller clas
     *
     * @return string
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Set controller class
     *
     * @param string $class
     * @return MvcEvent
     */
    public function setException($exception)
    {
        $this->exception = $exception;
        return $this;
    }
    
}
