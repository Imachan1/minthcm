<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Meetings';
$ESListViewDefs[$module_name] = array(
    'columns' => [
        'NAME' =>
        array(
            'width' => '25%',
            'label' => 'LBL_LIST_SUBJECT',
            'link' => true,
            'default' => true,
        ),
        'CONTACT_NAME' =>
        array(
            'width' => '15%',
            'label' => 'LBL_LIST_CONTACT',
            'link' => true,
            'id' => 'CONTACT_ID',
            'module' => 'Contacts',
            'default' => true,
            'ACLTag' => 'CONTACT',
        ),
        'PARENT_NAME' =>
        array(
            'width' => '15%',
            'label' => 'LBL_LIST_RELATED_TO',
            'dynamic_module' => 'PARENT_TYPE',
            'id' => 'PARENT_ID',
            'link' => true,
            'default' => true,
            'sortable' => false,
            'ACLTag' => 'PARENT',
            'related_fields' =>
            array(
                'parent_id',
                'parent_type',
            ),
        ),
        'DATE_START' =>
        array(
            'width' => '10%',
            'label' => 'LBL_LIST_DATE',
            'link' => false,
            'default' => true,
            'related_fields' =>
            array(
                'time_start',
            ),
        ),
        'ASSIGNED_USER_NAME' =>
        array(
            'width' => '10%',
            'label' => 'LBL_LIST_ASSIGNED_TO_NAME',
            'module' => 'Employees',
            'id' => 'ASSIGNED_USER_ID',
            'default' => true,
            'link' => true
        ),
        'DIRECTION' =>
        array(
            'type' => 'enum',
            'label' => 'LBL_LIST_DIRECTION',
            'width' => '10%',
            'default' => false,
        ),
        'STATUS' =>
        array(
            'type' => 'ColoredActivityStatus',
            'width' => '10%',
            'label' => 'LBL_LIST_STATUS',
            'link' => false,
            'default' => false,
        ),
        'TYPE' =>
        array(
            'width' => '10%',
            'label' => 'LBL_TYPE',
            'link' => false,
            'default' => false,
        ),
        'DATE_ENTERED' => array(
            'width' => '10%',
            'label' => 'LBL_DATE_ENTERED',
            'default' => true
        ),
    ]
);
