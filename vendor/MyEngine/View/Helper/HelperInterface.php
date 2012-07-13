<?php
namespace MyEngine\View\Helper;

use MyEngine\View\RendererInterface;

interface HelperInterface
{
    /**
     * Set the View object
     *
     * @param  Renderer $view
     * @return HelperInterface
     */
    public function setView(RendererInterface $view);

    /**
     * Get the View object
     *
     * @return Renderer
     */
    public function getView();
}
