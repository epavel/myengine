<?php

namespace MyEngine\View;

interface ResolverInterface
{
    /**
     * Resolve a template/pattern name to a resource the renderer can consume
     * 
     * @param  string $name 
     * @param  null|Renderer $renderer 
     * @return mixed
     */
    public function resolve($name, RendererInterface $renderer = null);
}
