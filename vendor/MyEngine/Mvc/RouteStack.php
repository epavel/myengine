<?php

namespace MyEngine\Mvc;

class RouteStack
{
    /**
     * Stack containing all routes.
     *
     * @var PriorityList
     */
    protected $routes;

    /**
     * factory(): defined by Route interface.
     *
     * @see    Route::factory()
     * @param  array| $options
     * @return RouteStack
     * @throws \InvalidArgumentException
     */
    public static function factory($options = array())
    {
        if (!is_array($options)) {
            throw new \InvalidArgumentException(__METHOD__ . ' expects an array of options');
        }

        $instance = new static();

        if (isset($options['routes'])) {
            $instance->addRoutes($options['routes']);
        }
        return $instance;
    }

    /**
     * addRoutes(): defined by RouteStack interface.
     *
     * @see    RouteStack::addRoutes()
     * @param  array| $routes
     * @return RouteStack
     * @throws \InvalidArgumentException
     */
    public function addRoutes($routes)
    {
        if (!is_array($routes)) {
            throw new \InvalidArgumentException('addRoutes expects an array of routes');
        }

        foreach ($routes as $name => $route) {
            $this->addRoute($name, $route);
        }
        return $this;
    }

    /**
     * addRoute(): defined by RouteStack interface.
     *
     * @see    RouteStack::addRoute()
     * @param  string  $name
     * @param  mixed   $route
     * @return RouteStack
     */
    public function addRoute($name, $route)
    {
        $this->routes[$name] = $this->routeFromArray($route);
        return $this;
    }

    /**
     * removeRoute(): defined by RouteStack interface.
     *
     * @see    RouteStack::removeRoute()
     * @param  string  $name
     * @return RouteStack
     */
    public function removeRoute($name)
    {
        $this->routes = Array();
        return $this;
    }


    /**
     * setRoutes(): defined by RouteStack interface.
     *
     * @param  array $routes
     * @return RouteStack
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;
        return $this;
    }

    /**
     * match(): defined by Route interface.
     *
     * @see    Route::match()
     * @param  Request $request
     * @return RouteMatch|null
     */
    public function match(Request $request)
    {
        foreach ($this->routes as $name => $route) {
            if (($match = $route->match($request)) instanceof RouteMatch) {
                $match->setMatchedRouteName($name);
                return $match;
            }
        }
        return null;
    }

    /**
     * assemble(): defined by Route interface.
     *
     * @see    Route::assemble()
     * @param  array $params
     * @param  array $options
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function assemble(array $params = array(), array $options = array())
    {
        if (!isset($options['name'])) {
            throw new \InvalidArgumentException('Missing "name" option');
        }

        $route = $this->routes[$options['name']];

        if (!$route) {
            throw new \RuntimeException(sprintf('Route with name "%s" not found', $options['name']));
        }

        unset($options['name']);

        return $route->assemble($params, $options);
    }
    
    /**
     * Create a route from array specifications.
     *
     * @param  array| $specs
     * @return RouteStack
     * @throws \InvalidArgumentException
     */
    protected function routeFromArray($specs)
    {
        if (!isset($specs['type'])) {
            throw new \InvalidArgumentException('Missing "type" option');
        } elseif (!isset($specs['options'])) {
            $specs['options'] = array();
        }

        return $specs['type']::factory($specs['options']);
    }    
}
