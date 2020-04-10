<?php

function getModuleList()
{
    static $modules = null;
    if (!$modules) {
        global $moduleList;

        $modules = array();
        $modules[''] = '';
        foreach ($moduleList as $module) {
            $modules[$module] = $module;
        }
    }
    return $modules;
}
