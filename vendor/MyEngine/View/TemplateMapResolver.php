<?php

namespace MyEngine\View;

class TemplateMapResolver implements ResolverInterface
{
    /**
     * @var array
     */
    protected $map = array();

    /**
     * Constructor
     *
     * Instantiate and optionally populate template map.
     * 
     * @param  array $map 
     * @return void
     */
    public function __construct($map = array())
    {
        $this->setMap($map);
    }

    /**
     * Set (overwrite) template map
     *
     * Maps should be arrays or Traversable objects with name => path pairs
     * 
     * @param  array $map 
     * @return TemplateMapResolver
     */
    public function setMap($map)
    {
        if (!is_array($map)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an array, received "%s"',
                __METHOD__,
                (is_object($map) ? get_class($map) : gettype($map))
            ));
        }

        $this->map = $map;
        return $this;
    }

    /**
     * Add an entry to the map
     * 
     * @param  string|array|Traversable $nameOrMap 
     * @param  null|string $path 
     * @return TemplateResolver
     */
    public function add($nameOrMap, $path = null)
    {
        if (is_array($nameOrMap)) {
            $this->merge($nameOrMap);
            return $this;
        }

        if (!is_string($nameOrMap)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects a string or array for the first argument; received "%s"',
                __METHOD__,
                (is_object($map) ? get_class($map) : gettype($map))
            ));
        }

        if (empty($path)) {
            if (isset($this->map[$nameOrMap])) {
                unset($this->map[$nameOrMap]);
            }
            return $this;
        }

        $this->map[$nameOrMap] = $path;
        return $this;
    }

    /**
     * Merge internal map with provided map
     * 
     * @param  array $map 
     * @return TemplateMapResolver
     */
    public function merge($map)
    {
        if (!is_array($map)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an array, received "%s"',
                __METHOD__,
                (is_object($map) ? get_class($map) : gettype($map))
            ));
        }

        $this->map = array_replace_recursive($this->map, $map);
        return $this;
    }

    /**
     * Does the resolver contain an entry for the given name?
     * 
     * @param  string $name 
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->map);
    }

    /**
     * Retrieve a template path by name
     * 
     * @param  string $name 
     * @return false|string
     * @throws Exception\DomainException if no entry exists
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            return false;
        }
        return $this->map[$name];
    }

    /**
     * Retrieve the template map
     * 
     * @return array
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Resolve a template/pattern name to a resource the renderer can consume
     * 
     * @param  string $name 
     * @param  null|Renderer $renderer 
     * @return string
     */
    public function resolve($name, RendererInterface $renderer = null)
    {
        return $this->get($name);
    }
}
