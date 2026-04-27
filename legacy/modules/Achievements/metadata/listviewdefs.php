<?php

$module_name = 'Achievements';
$listViewDefs[$module_name] = array(
    'NAME' => array(
        'width' => '30%',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true,
    ),
    'CATEGORY' => array(
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_CATEGORY',
        'width' => '15%',
    ),
    'AWARD_TYPE' => array(
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_AWARD_TYPE',
        'width' => '15%',
    ),
    'VISIBILITY' => array(
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_VISIBILITY',
        'width' => '10%',
    ),
    'ACTIVE' => array(
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_ACTIVE',
        'width' => '8%',
    ),
    'ASSIGNED_USER_NAME' => array(
        'width' => '12%',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true,
    ),
    'DATE_MODIFIED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => false,
    ),
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => false,
    ),
    'CREATED_BY_NAME' => array(
        'label' => 'LBL_CREATED',
        'width' => '10%',
        'default' => false,
    ),
    'MODIFIED_BY_NAME' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_MODIFIED_NAME',
        'id' => 'MODIFIED_USER_ID',
        'width' => '10%',
        'default' => false,
    ),
);
