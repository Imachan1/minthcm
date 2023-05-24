<?php

namespace MintHCM\Data;

use MintHCM\Data\MintBean;
use \BeanFactory as LegacyFactory;

class BeanFactory
{
    public static function getBean($module, $id = null, $params = array(), $deleted = true)
    {
        chdir('../legacy/');
        $legacy_bean = LegacyFactory::getBean($module, $id, $params, $deleted);
        chdir('../api/');

        return new MintBean($legacy_bean);
    }

    public static function newBean($module)
    {
        return self::getBean($module);
    }

    public static function __callStatic($name, $arguments)
    {
        chdir('../legacy/');
        $reposnse = LegacyFactory::$name($arguments);
        chdir('../api/');

        return $response;
    }
}
