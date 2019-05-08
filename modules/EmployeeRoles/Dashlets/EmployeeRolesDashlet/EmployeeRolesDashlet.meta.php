<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $app_strings;

$dashletMeta['EmployeeRolesDashlet'] = array(
    'module' => 'EmployeeRoles',
    'title' => translate('LBL_HOMEPAGE_TITLE', 'EmployeeRoles'),
    'description' => 'A customizable view into Roles',
    'category' => 'Module Views'
);