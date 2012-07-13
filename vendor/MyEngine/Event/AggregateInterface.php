<?php

namespace MyEngine\Event;

interface AggregateInterface
{
    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManager $eventManager
     */
    public function attach(EventManager $eventManager);
}
