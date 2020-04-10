<?php

function getModuleList()
{
    global $moduleList;

    $modules = array();
    foreach ($moduleList as $module) {
        $modules[$module] = $module;
    }

    return $modules;
}
