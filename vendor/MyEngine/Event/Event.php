<?php

namespace MyEngine\Event;

class Event
{	
    private $stopped = false;

    /**
     * @var string|object The event target
     */
    protected $target;

    public function getTarget()
    {
        return $this->target;
    }

    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }
	
    public function isStopped()
    {
        return $this->stopped;
    }

    public function stop()
    {
        $this->stopped = true;
    }
}
