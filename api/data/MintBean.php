<?php

namespace MintHCM\Data;

require_once '../legacy/data/SugarBean.php';

class MintBean
{
    protected static $static_legacy_bean;

    public function __construct(protected $legacy_bean)
    {
        static::$static_legacy_bean = $legacy_bean;
    }

    public function __get($name)
    {
        chdir('../legacy/');
        $response = $this->legacy_bean->$name;
        chdir('../api/');

        return $response;
    }

    public function __set($name, $value)
    {
        if ('legacy_bean' === $name) {
            $this->legacy_bean = $value;
            return;
        }

        chdir('../legacy/');
        $this->legacy_bean->$name = $value;
        chdir('../api/');
    }

    public function __isset($name)
    {
        chdir('../legacy/');
        $response = isset($this->legacy_bean->$name);
        chdir('../api/');

        return $response;
    }

    public function __unset($name)
    {
        chdir('../legacy/');
        unset($this->legacy_bean->$name);
        chdir('../api/');
    }

    public function __call($name, $arguments)
    {
        chdir('../legacy/');
        $response = $this->legacy_bean->$name(...$arguments);
        chdir('../api/');

        return $response;
    }

    public static function __callStatic($name, $arguments)
    {
        chdir('../legacy/');
        $response = static::$static_legacy_bean::$name(...$arguments);
        chdir('../api/');

        return $response;
    }
}
