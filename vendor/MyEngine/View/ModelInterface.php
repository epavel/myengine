<?php

namespace MyEngine\View;

use Countable;
use IteratorAggregate;

interface ModelInterface extends Countable, IteratorAggregate
{
    /**
     * Set renderer option/hint
     * 
     * @param  string $name 
     * @param  mixed $value 
     * @return ModelInterface
     */
    public function setOption($name, $value);

    /**
     * Set renderer options/hints en masse
     * 
     * @param  array|\Traversable $name 
     * @return ModelInterface
     */
    public function setOptions($options);

    /**
     * Get renderer options/hints
     * 
     * @return array|\Traversable
     */
    public function getOptions();
     
    /**
     * Set view variable
     * 
     * @param  string $name 
     * @param  mixed $value 
     * @return ModelInterface
     */
    public function setVariable($name, $value);

    /**
     * Set view variables en masse
     * 
     * @param  array|\ArrayAccess $variables 
     * @return ModelInterface
     */
    public function setVariables($variables);

    /**
     * Get view variables
     * 
     * @return array|\ArrayAccess
     */
    public function getVariables();

    /**
     * Set the template to be used by this model 
     * 
     * @param  string $template
     * @return ModelInterface
     */
    public function setTemplate($template);

    /**
     * Get the template to be used by this model
     * 
     * @return string
     */
    public function getTemplate();

    /**
     * Add a child model
     * 
     * @param  ModelInterface $child
     * @param  null|string $captureTo Optional; if specified, the "capture to" value to set on the child
     * @return ModelInterface
     */
    public function addChild(ModelInterface $child, $captureTo = null);

    /**
     * Return all children.
     *
     * Return specifies an array, but may be any iterable object.
     *
     * @return array
     */
    public function getChildren();

    /**
     * Does the model have any children? 
     * 
     * @return bool
     */
    public function hasChildren();

    /**
     * Set the name of the variable to capture this model to, if it is a child model
     * 
     * @param  string $capture 
     * @return ModelInterface
     */
    public function setCaptureTo($capture);

    /**
     * Get the name of the variable to which to capture this model
     * 
     * @return string
     */
    public function captureTo();

    /**
     * Set flag indicating whether or not this is considered a terminal or standalone model
     * 
     * @param  bool $terminate 
     * @return ModelInterface
     */
    public function setTerminal($terminate);

    /**
     * Is this considered a terminal or standalone model?
     * 
     * @return bool
     */
    public function terminate();
}