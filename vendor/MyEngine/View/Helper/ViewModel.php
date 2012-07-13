<?php

namespace MyEngine\View\Helper;

use MyEngine\View\ModelInterface;

class ViewModel extends AbstractHelper
{
    /**
     * @var Model
     */
    protected $current;

    /**
     * @var Model
     */
    protected $root;

    /**
     * Get the root view model
     * 
     * @return null|Model
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Is a root view model composed?
     * 
     * @return bool
     */
    public function hasRoot()
    {
        return ($this->root instanceof ModelInterface);
    }

    /**
     * Get the current view model
     * 
     * @return null|Model
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * Is a current view model composed?
     * 
     * @return bool
     */
    public function hasCurrent()
    {
        return ($this->current instanceof ModelInterface);
    }

    /**
     * Set the root view model
     * 
     * @param  Model $model 
     * @return ViewModel
     */
    public function setRoot(ModelInterface $model)
    {
        $this->root = $model;
        return $this;
    }

    /**
     * Set the current view model
     * 
     * @param  Model $model 
     * @return ViewModel
     */
    public function setCurrent(ModelInterface $model)
    {
        $this->current = $model;
        return $this;
    }
}
