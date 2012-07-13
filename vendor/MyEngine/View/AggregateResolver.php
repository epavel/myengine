<?php

namespace MyEngine\View;

use Countable;
use IteratorAggregate;
use MyEngine\Stdlib\PriorityList;

class AggregateResolver implements Countable, IteratorAggregate, ResolverInterface
{
    /**
     * @var PriorityList
     */
    protected $queue;

    /**
     * Constructor
     *
     * Instantiate the internal priority queue
     * 
     * @return void
     */
    public function __construct()
    {
        $this->queue = new PriorityList();
    }

    /**
     * Return count of attached resolvers
     * 
     * @return void
     */
    public function count()
    {
        return $this->queue->count();
    }

    /**
     * IteratorAggregate: return internal iterator
     * 
     * @return Traversable
     */
    public function getIterator()
    {
        return $this->queue;
    }

    /**
     * Attach a resolver
     * 
     * @param  Resolver $resolver 
     * @param  int $priority 
     * @return AggregateResolver
     */
    public function attach(ResolverInterface $resolver, $priority = 1)
    {
        $this->queue->insert($resolver, $priority);
        return $this;
    }

    /**
     * Resolve a template/pattern name to a resource the renderer can consume
     * 
     * @param  string $name 
     * @param  null|RendererInterface $renderer 
     * @return false|string
     */
    public function resolve($name, RendererInterface $renderer = null)
    {
        if (0 === count($this->queue)) {
            return false;
        }

        foreach ($this->queue as $resolver) {
            $resource = $resolver->resolve($name, $renderer);
            
            if (!$resource) {
                continue;
            }

            return $resource;
        }

        return false;
    }
}
