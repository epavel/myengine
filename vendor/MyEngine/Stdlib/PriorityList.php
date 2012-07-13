<?php

namespace MyEngine\Stdlib;

use Countable;
use Iterator;

class PriorityList implements Countable, Iterator
{
    private $position = 0;
    
    private $items = array();
    
    private $sorted = array();
         
    public function __construct() {
        $this->position = 0;
    }

    function rewind() {
        $this->position = 0;
    }

    function current() {
        if (!isset($this->sorted)) {
            $this->sorting($key);
        }
    	return $this->sorted[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        if (!isset($this->sorted)) {
            $this->sorting($key);
        }
    	return isset($this->sorted[$this->position]);
    }         
         
    public function insert($value, $priority = 0)
    {
        $this->items[$priority][] = $value;
        unset($this->sorted);
    }
    
    public function remove($value)
    {
        if (!count($this->items)) {
            return;
        }

        foreach ($this->items as $priority => $values) {
            if (false !== ($index = array_search($value, $values))) {
                unset($this->items[$priority][$index], $this->sorted);
            }
        }
    }	
    
    public function get()
    {
        if (!isset($this->sorted)) {
            $this->sorting($key);
        }
        return $this->sorted;
    }

    public function count()
    {
        return count($this->get());
    }
	
    private function sorting()
    {
        $this->sorted = array();
        if (count($this->items)) {
            krsort($this->items);
            $this->sorted = call_user_func_array('array_merge', $this->items);
        }
    }
}
