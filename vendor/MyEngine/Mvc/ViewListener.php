<?php

namespace MyEngine\Mvc;

use MyEngine\Event\EventManager;
use MyEngine\Event\AggregateInterface;
use MyEngine\View\HelperManager;
use MyEngine\View\TemplatePathStack;
use MyEngine\View\TemplateMapResolver;
use MyEngine\View\AggregateResolver;
use MyEngine\View\PhpRenderer;
use MyEngine\View\View;
use MyEngine\View\PhpRendererListener;

class ViewListener implements AggregateInterface
{
    /**
     * @var object application configuration service
     */
    protected $config;

	protected $routeNotFoundListener;
	protected $exceptionListener;
	protected $viewModelListener;
	protected $injectViewModelListener;
	    
    /**
     * Attach the aggregate to the specified event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function attach(EventManager $eventManager)
    {
        $eventManager->attach('bootstrap', array($this, 'onBootstrap'), 10000);
    }

    /**
     * Prepares the view layer
     *
     * @param  $event
     * @return void
     */
    public function onBootstrap(MvcEvent $event)
    {
        $application  = $event->getApplication();
        $config       = $application->getConfig();
        $this->eventManager = $eventManager = $application->getEventManager();

        $this->config   = isset($config['view']) && (is_array($config['view']) || $config['view'] instanceof ArrayAccess) ? $config['view'] : array();
        $this->event    = $event;

        $routeNotFoundListener = $this->getRouteNotFoundListener();
        $eventManager->attachAggregate($routeNotFoundListener);        
        $eventManager->attachAggregate($this->getExceptionListener());
        
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(new CreateViewModelListener(), 'createViewModelFromArray'), -80);        

        
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(new InjectTemplateListener(), 'injectTemplate'), -90);
        
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($routeNotFoundListener, 'prepareNotFoundViewModel'), -90);
        
        $injectViewModelListener = new InjectViewModelListener();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($injectViewModelListener, 'injectViewModel'), -100);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($injectViewModelListener, 'injectViewModel'), -100);
        
        $mvcRenderingListener    = $this->getMvcRenderingListener();
        $eventManager->attachAggregate($mvcRenderingListener);
    }
    
    
    /**
     * Instantiates and configures the "route not found", or 404, strategy
     *
     * @return RouteNotFoundStrategy
     */
    public function getRouteNotFoundListener()
    {
        if ($this->routeNotFoundListener) {
            return $this->routeNotFoundListener;
        }

        $this->routeNotFoundListener = new RouteNotFoundListener();

        $displayNotFoundReason = false;
        $notFoundTemplate      = '404';

        if (isset($this->config['display_not_found_reason'])) {
            $displayNotFoundReason = $this->config['display_not_found_reason'];
        }
        if (isset($this->config['not_found_template'])) {
            $notFoundTemplate = $this->config['not_found_template'];
        }

        $this->routeNotFoundListener->setDisplayNotFoundReason($displayNotFoundReason);
        $this->routeNotFoundListener->setNotFoundTemplate($notFoundTemplate);

        return $this->routeNotFoundListener;
    }

    /**
     * Instantiates and configures the exception strategy
     *
     * @return ExceptionStrategy
     */
    public function getExceptionListener()
    {
        if ($this->exceptionListener) {
            return $this->exceptionListener;
        }

        $this->exceptionListener = new ExceptionListener();

        $displayExceptions = false;
        $exceptionTemplate = 'error';

        if (isset($this->config['display_exceptions'])) {
            $displayExceptions = $this->config['display_exceptions'];
        }
        if (isset($this->config['exception_template'])) {
            $exceptionTemplate = $this->config['exception_template'];
        }

        $this->exceptionListener->setDisplayExceptions($displayExceptions);
        $this->exceptionListener->setExceptionTemplate($exceptionTemplate);

        return $this->exceptionListener;
    }    
    
    /**
     * Instantiates and configures the default MVC rendering strategy
     *
     * @return DefaultRenderingStrategy
     */
    public function getMvcRenderingListener()
    {
        if ($this->mvcRenderingStrategy) {
            return $this->mvcRenderingStrategy;
        }

        $this->mvcRenderingListener = new DefaultRenderingListener($this->getView());
        $this->mvcRenderingListener->setLayoutTemplate($this->getLayoutTemplate());

        return $this->mvcRenderingListener;
    }

    /**
     * Instantiates and configures the view
     *
     * @return View
     */
    public function getView()
    {
        if ($this->view) {
            return $this->view;
        }

        $this->view = new View();
        $this->view->setEventManager($this->eventManager);
        $this->view->getEventManager()->attachAggregate($this->getRendererListener());

        return $this->view;
    }    
    
    /**
     * Retrieves the layout template name from the configuration
     *
     * @return string
     */
    public function getLayoutTemplate()
    {
        $layout = 'layout/layout';
        if (isset($this->config['layout'])) {
            $layout = $this->config['layout'];
        }
        return $layout;
    }

    public function getRendererListener()
    {
        if ($this->rendererListener) {
            return $this->rendererListener;
        }

        $this->rendererListener = new PhpRendererListener(
            $this->getRenderer()
        );

        return $this->rendererListener;
    }

    /**
     * Instantiates and configures the renderer
     *
     * @return ViewPhpRenderer
     */
    public function getRenderer()
    {
        if ($this->renderer) {
            return $this->renderer;
        }

        $this->renderer = new PhpRenderer;
        $this->renderer->setHelperManager($this->getHelperManager());
        $this->renderer->setResolver($this->getResolver());

        $model       = $this->getViewModel();
        $modelHelper = $this->renderer->plugin('viewmodel');
        $modelHelper->setRoot($model);
        return $this->renderer;
    }

    /**
     * Instantiates and configures the renderer's resolver
     *
     * @return AggregateResolver
     */
    public function getResolver()
    {
        if ($this->resolver) {
            return $this->resolver;
        }

        $map = array();
        if (isset($this->config['template_map'])) {
            $map = $this->config['template_map'];
        }
        $templateMapResolver = new TemplateMapResolver($map);

        $stack = array();
        if (isset($this->config['template_path_stack'])) {
            $stack = $this->config['template_path_stack'];
        }
        $templatePathStack = new TemplatePathStack();
        $templatePathStack->addPaths($stack);

        $this->resolver = new AggregateResolver();
        $this->resolver->attach($templateMapResolver);
        $this->resolver->attach($templatePathStack);

        return $this->resolver;
    }

    /**
     * Instantiates and configures the renderer's helper manager
     *
     * @return HelperManager
     */
    public function getHelperManager()
    {
        if ($this->helperManager) {
            return $this->helperManager;
        }        
        $this->helperManager = new HelperManager();
        return $this->helperManager;
    }

    /**
     * Configures the MvcEvent view model to ensure it has the template injected
     *
     * @return ModelInterface
     */
    public function getViewModel()
    {
        if ($this->viewModel) {
            return $this->viewModel;
        }

        $this->viewModel = $model = $this->event->getViewModel();
        $model->setTemplate($this->getLayoutTemplate());

        return $this->viewModel;
    }    
}
