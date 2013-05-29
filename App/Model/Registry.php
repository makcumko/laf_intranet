<?php
namespace App\Model;

class Registry
{
    protected static $registry;

    public static function Set($name, $value) {
    	self::$registry[$name] = $value;
    }

    public static function Get($name) {
    	if (isset(self::$registry[$name])) return self::$registry[$name];
    }

    public static function Singleton($className) {
        if (isset(self::$registry["::class_".$className])) return self::$registry["::class_".$className];

        self::$registry["::class_".$className] = new $className();
        return self::$registry["::class_".$className];
    }
}