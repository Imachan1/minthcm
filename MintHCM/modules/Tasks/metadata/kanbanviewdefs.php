<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $app_list_strings;

$module_name = 'Tasks';
$kanbanViewDefs[$module_name] = array(
    'columns_field' => 'status',
    'order_field' => '',
    'columns' => $app_list_strings['task_status_dom'],
    'black_list' => array(
    ),
    'expired_excluded_columns' => array(
        'Completed'
    ),
    'roles_actions' => array(
    ),
    'roles_allow_create' => array(
    ),
    'required_fields' => array(
        'name'
    ),
);
