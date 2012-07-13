<?php

namespace MyEngine\View;

class TemplatePathStack implements ResolverInterface
{
    /**
     * Default suffix to use
     *
     * Appends this suffix if the template requested does not use it.
     * 
     * @var string
     */
    protected $defaultSuffix = 'phtml';

    /**
     * @var SplStack
     */
    protected $paths;

    /**
     * Constructor
     *
     * @param  null|array|Traversable $options
     * @return void
     */
    public function __construct($options = null)
    {

        $this->paths = Array();
        if (null !== $options) {
            $this->setOptions($options);
        }
    }

    /**
     * Configure object
     *
     * @param  array $options
     * @return void
     * @throws Exception\InvalidArgumentException
     */
    public function setOptions($options)
    {
        if (!is_array($options)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected array, received "%s"',
                (is_object($options) ? get_class($options) : gettype($options))
            ));
        }

        foreach ($options as $key => $value) {
            switch (strtolower($key)) {
                case 'script_paths':
                    $this->addPaths($value);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Set default file suffix
     *
     * @param  string $defaultSuffix
     * @return TemplatePathStack
     */
    public function setDefaultSuffix($defaultSuffix)
    {
        $this->defaultSuffix = ltrim((string) $defaultSuffix, '.');
        return $this;
    }
    
    /**
     * Get default file suffix
     *
     * @return string
     */
    public function getDefaultSuffix()
    {
        return $this->defaultSuffix;
    }

    /**
     * Add many paths to the stack at once
     *
     * @param  array $paths
     * @return TemplatePathStack
     */
    public function addPaths(array $paths)
    {
        foreach ($paths as $path) {
            $this->addPath($path);
        }
        return $this;
    }

    /**
     * Rest the path stack to the paths provided
     *
     * @param  array $paths
     * @return TemplatePathStack
     * @throws Exception\InvalidArgumentException
     */
    public function setPaths($paths)
    {
        if (is_array($paths)) {
            $this->paths = $paths;
        } else {
            throw new \InvalidArgumentException(
                "Invalid argument provided for \$paths, expecting either an array or SplStack object"
            );
        }

        return $this;
    }

    /**
     * Normalize a path for insertion in the stack
     *
     * @param  string $path
     * @return string
     */
    public static function normalizePath($path)
    {
        $path = rtrim($path, '/');
        $path = rtrim($path, '\\');
        $path .= DIRECTORY_SEPARATOR;
        return $path;
    }

    /**
     * Add a single path to the stack
     *
     * @param  string $path
     * @return TemplatePathStack
     * @throws Exception\InvalidArgumentException
     */
    public function addPath($path)
    {
        if (!is_string($path)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid path provided, must be a string, received %s',
                gettype($path)
            ));
        }
        $this->paths[] = static::normalizePath($path);
        return $this;
    }

    /**
     * Clear all paths
     *
     * @return void
     */
    public function clearPaths()
    {
        $this->paths = array();
    }

    /**
     * Returns stack of paths
     *
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * Retrieve the filesystem path to a view script
     *
     * @param  string $name
     * @param  null|Renderer $renderer
     * @return string
     * @throws Exception\RuntimeException
     */
    public function resolve($name, RendererInterface $renderer = null)
    {
        if (!count($this->paths)) {
            return false;
        }

        // Ensure we have the expected file extension
        $defaultSuffix = $this->getDefaultSuffix();
        if (pathinfo($name, PATHINFO_EXTENSION) != $defaultSuffix) {
            $name .= '.' . $defaultSuffix;
        }

        foreach ($this->paths as $path) {
        	$filePath = $path . $name;
            if (!file_exists($filePath)) {
                break;
            }
            return $filePath;
        }
        
        return false;
    }
}
