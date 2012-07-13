<?php

namespace MyEngine\View;

interface RendererInterface
{
    /**
     * Return the template engine object, if any
     *
     * If using a third-party template engine, such as Smarty, patTemplate,
     * phplib, etc, return the template engine object. Useful for calling
     * methods on these objects, such as for setting filters, modifiers, etc.
     *
     * @return mixed
     */
    public function getEngine();

    /**
     * Set the resolver used to map a template name to a resource the renderer may consume.
     * 
     * @param  Resolver $resolver 
     * @return RendererInterface
     */
    public function setResolver(ResolverInterface $resolver);

    /**
     * Processes a view script and returns the output.
     *
     * @param  string|Model $name The script/resource process, or a view model
     * @param  null|array Values to use during rendering
     * @return string The script output.
     */
    public function render($nameOrModel, $values = null);
}
