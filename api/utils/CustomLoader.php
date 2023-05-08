<?php

namespace MintHCM\Utils;

class CustomLoader
{
    public static function getObject($class, ...$args)
    {
        $classReflection = new \ReflectionClass($class);
        $custom_class = str_replace('MintHCM', 'MintHCM\Custom', $classReflection->getName());
        if (class_exists($custom_class) && is_subclass_of($custom_class, $class)) {
            return new $custom_class(...$args);
        }
        return new $class(...$args);
    }
}
