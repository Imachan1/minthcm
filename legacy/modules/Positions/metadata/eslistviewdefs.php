<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Positions';
$ESListViewDefs[$module_name] = array(
    'columns' => array(
        'NAME' => array(
            'name' => 'name',
            'label' => 'LBL_NAME',
            'default' => true,
            'enabled' => true,
            'link' => true,
        ),
        'STATUS' => array(
            'label' => 'LBL_STATUS',
            'default' => true,
        ),
        'SECURITYGROUP_LEADER_NAME' => array(
            'name' => 'securitygroup_leader_name',
            'label' => 'LBL_SECURITYGROUPS_LEADER_NAME',
            'id' => 'SECURITYGROUPS_LEADER_ID',
            'enabled' => true,
            'default' => true,
            'link' => true
        ),
        'OFFBOARDINGTEMPLATE_NAME' => array(
            'name' => 'offboardingtemplate_name',
            'label' => 'LBL_OFFBOARDINGTEMPLATE_NAME',
            'enabled' => true,
            'default' => false,
        ),
        'ONBOARDINGTEMPLATE_NAME' => array(
            'name' => 'onboardingtemplate_name',
            'label' => 'LBL_ONBOARDINGTEMPLATE_NAME',
            'enabled' => true,
            'default' => false,
        ),
        'POSITIONS_SUPERVISION_NAME' => array(
            'name' => 'positions_supervision_name',
            'label' => 'LBL_POSITIONS_SUPERVISION_NAME',
            'enabled' => true,
            'default' => true,
            'link' => true
        ),
        'ASSIGNED_USER_NAME' => array(
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
            'default' => true,
            'enabled' => true,
            'link' => true,
        ),
        'DATE_MODIFIED' => array(
            'label' => 'LBL_DATE_MODIFIED',
            'enabled' => true,
            'default' => true,
            'name' => 'date_modified',
            'readonly' => true,
        ),
    )
);
