<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Calls';
$ESListViewDefs[$module_name] = array(
    'columns' => array(
        'NAME' => array(
            'width' => '40%',
            'label' => 'LBL_LIST_SUBJECT',
            'link' => true,
            'default' => true,
        ),
        'DIRECTION' => array(
            'width' => '10%',
            'label' => 'LBL_LIST_DIRECTION',
            'link' => false,
            'default' => true,
        ),
        'CONTACT_NAME' => array(
            'width' => '20%',
            'label' => 'LBL_LIST_CONTACT',
            'link' => true,
            'id' => 'CONTACT_ID',
            'module' => 'Contacts',
            'default' => true,
            'ACLTag' => 'CONTACT',
        ),
        'PARENT_NAME' => array(
            'width' => '20%',
            'label' => 'LBL_LIST_RELATED_TO',
            'dynamic_module' => 'PARENT_TYPE',
            'id' => 'PARENT_ID',
            'link' => true,
            'default' => true,
            'sortable' => false,
            'ACLTag' => 'PARENT',
            'related_fields' => array(
                'parent_id',
                'parent_type',
            ),
        ),
        'DATE_START' => array(
            'width' => '15%',
            'label' => 'LBL_LIST_DATE',
            'link' => false,
            'default' => true,
            'related_fields' => array(
                'time_start',
            ),
        ),
        'ASSIGNED_USER_NAME' => array(
            'width' => '2%',
            'label' => 'LBL_LIST_ASSIGNED_TO_NAME',
            'module' => 'Employees',
            'id' => 'ASSIGNED_USER_ID',
            'default' => true,
            'link' => true
        ),
        'STATUS' => array(
            'width' => '10%',
            'label' => 'LBL_STATUS',
            'link' => false,
            'default' => false,
        ),
        'DATE_ENTERED' => array(
            'width' => '10%',
            'label' => 'LBL_DATE_ENTERED',
            'default' => true
        ),
        'duration_minutes' => [
            'label' => 'LBL_DURATION_MINUTES',
            'default' => true,
        ],
    ),
    'search' => array(
        'date_entered' => array(
            'type' => 'date',
            'label' => 'LBL_DATE_ENTERED',
            'key' => 'meta.created.date'
        ),
        'date_start' => array(
            'type' => 'date',
            'label' => 'LBL_LIST_DATE',
            'key' => 'date_start'
        ),
        'date_end' => array(
            'type' => 'date',
            'label' => 'LBL_DATE_END',
            'key' => 'date_end'
        ),
        'direction' => array(
            'type' => 'enum',
            'label' => 'LBL_DIRECTION',
            'key' => 'direction.keyword'
        ),
        'status' => array(
            'type' => 'enum',
            'label' => 'LBL_STATUS',
            'key' => 'status.keyword'
        ),
        'duration_minutes' => array(
            'type' => 'numeric',
            'key' => 'duration_minutes'
        ),
    ),
);
