<?php

namespace MyEngine\View;

class HelperManager
{
    protected $helperInstances = array();

    /**
     * Default set of helpers
     * 
     * @var array
     */
    protected $helpersMap = array(
        'viewmodel' => 'MyEngine\View\Helper\ViewModel',
        'action'    => 'MyEngine\View\Helper\Action',
    );

    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * Retrieve renderer instance
     * 
     * @return null|RendererInterface
     */
    public function get($helperName, $options = null)
    {
        if(! isset($this->helperInstances[$helperName])) {
            $this->helperInstances[$helperName] = new $this->helpersMap[$helperName]($options);
        }
        
        $helper = new $this->helpersMap[$helperName]($options);
        
        if (! $helper instanceof Helper\HelperInterface) {
            throw new \Exception(sprintf(
                'Plugin of type %s is invalid, must implement %s\Helper\HelperInterface',
                (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
                __NAMESPACE__
            ));
        }
        
        $helper->setView($this->getRenderer());
        return $helper;
    }    
    
    /**
     * Set renderer
     * 
     * @param  RendererInterface $renderer 
     * @return HelperManager
     */
    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * Retrieve renderer instance
     * 
     * @return null|RendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }
}
