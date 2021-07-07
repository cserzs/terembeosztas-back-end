<?php

class TBConfig {

    private static $values = array();
    
    public static function get($name) {
        if (isset(self::$values[$name])) return self::$values[$name];
        return null;
    }

    public static function set($name, $value) {
        self::$values[$name] = $value;
    }

}