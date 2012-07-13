<?php

namespace MyEngine\View\Helper;

use MyEngine\View\RendererInterface;

abstract class AbstractHelper implements HelperInterface
{
    /**
     * View object
     *
     * @var RendererInterface
     */
    protected $view = null;

    /**
     * Set the View object
     *
     * @param  RendererInterface $view
     * @return AbstractHelper
     */
    public function setView(RendererInterface $view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Get the view object
     * 
     * @return null|RendererInterface
     */
    public function getView()
    {
        return $this->view;
    }
}
