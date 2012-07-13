<?php

namespace MyEngine;

class Registry
{
    private static $data = array();
    
    public static function set($index, $value)
    {
        self::$data[$index] = $value;
    }

    public static function get($index)
    {
        return self::$data[$index];
    }

    public static function is($index)
    {
        return array_key_exists($index, self::$data);
    }
}
