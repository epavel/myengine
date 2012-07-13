<?php

namespace MyEngine\Event;

class EventManager
{
    private $listeners = array();
    
    private $sorted = array();
	
    public function attach($eventName, $listener, $priority = 0)
    {
        $this->listeners[$eventName][$priority][] = $listener;
        unset($this->sorted[$eventName]);
    }
    
	public function attachAggregate(AggregateInterface $aggregate, $priority = 1)
    {
        return $aggregate->attach($this, $priority);
    }    

    public function detach($eventName, $listener)
    {
        if (!isset($this->listeners[$eventName])) {
            return;
        }

        foreach ($this->listeners[$eventName] as $priority => $listeners) {
            if (false !== ($key = array_search($listener, $listeners))) {
                unset($this->listeners[$eventName][$priority][$key], $this->sorted[$eventName]);
            }
        }
    }
	
    public function trigger($eventName, Event $event = null, $callback = null)
    {
        if (!isset($this->listeners[$eventName])) {
            return;
        }

        if (null === $event) {
            $event = new Event();
        }

        $listeners = $this->getListeners($eventName);
        foreach ($listeners as $listener) {
            $result = call_user_func($listener, $event);
            
            if ($event->isStopped()) {
				break;
            }
            
            if ($callback && call_user_func($callback, $result)) {
                break;
            }            
        }
		return $result;
    }

    public function getListeners($eventName = null)
    {
        if (null !== $eventName) {
            if (!isset($this->sorted[$eventName])) {
                $this->sorting($eventName);
            }
            return $this->sorted[$eventName];
        }

        foreach (array_keys($this->listeners) as $eventName) {
            if (!isset($this->sorted[$eventName])) {
                $this->sorting($eventName);
            }
        }

        return $this->sorted;
    }

    public function hasListeners($eventName = null)
    {
        return (boolean) count($this->getListeners($eventName));
    }
	
    private function sorting($eventName)
    {
        $this->sorted[$eventName] = array();

        if (isset($this->listeners[$eventName])) {
            krsort($this->listeners[$eventName]);
            $this->sorted[$eventName] = call_user_func_array('array_merge', $this->listeners[$eventName]);
        }
    }
}
