<?php

namespace MintHCM\Utils;

class LegacyConnector
{
    protected $class;
    protected static $static_class;

    public function __construct($class_name, $link = null)
    {
        chdir('../legacy/');
        if (isset($link)) {
            require_once $link;
        }
        $this->class = new $class_name();
        static::$static_class = new $class_name();
        chdir('../api/');
    }

    public function __get($name)
    {
        chdir('../legacy/');
        $response = $this->class->$name;
        chdir('../api/');

        return $response;
    }

    public function __set($name, $value)
    {
        if ('class' === $name) {
            $this->class = $value;
            return;
        }

        chdir('../legacy/');
        $this->class->$name = $value;
        chdir('../api/');
    }

    public function __call($name, $arguments)
    {
        chdir('../legacy/');
        $response = $this->class->$name(...$arguments);
        chdir('../api/');

        return $response;
    }

    public static function __callStatic($name, $arguments)
    {
        chdir('../legacy/');
        $response = static::$static_class::$name(...$arguments);
        chdir('../api/');

        return $response;
    }
}
