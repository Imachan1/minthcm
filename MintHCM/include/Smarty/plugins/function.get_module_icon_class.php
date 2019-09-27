<?php

function smarty_function_get_module_icon_class($params, &$smarty)
{
    $module_name = $params['module_name'];
    $theme       = SugarThemeRegistry::get('SuiteP');
    if (isset($theme->fa_module_icons[$module_name])) {
        $class = "fas {$theme->fa_module_icons[$module_name]}";
    } else {
        $lower_module_name = str_replace('_', '-', strtolower($module_name));
        $class             = "suitepicon suitepicon-module-{$lower_module_name}";
    }
    return $class;
}
