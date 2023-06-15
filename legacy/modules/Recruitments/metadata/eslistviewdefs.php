<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Recruitments';
$ESListViewDefs[$module_name] = array(
    'columns' => array(
        'NAME' => array(
            'name' => 'name',
            'label' => 'LBL_NAME',
            'default' => true,
            'enabled' => true,
            'link' => true,
            'width' => '10%',
        ),
        'POSITION_NAME' => array(
            'name' => 'position_name',
            'label' => 'LBL_RECRUITMENTS_POSITIONS_FROM_POSITIONS_TITLE',
            'enabled' => true,
            'id' => 'position_id',
            'link' => true,
            'sortable' => false,
            'default' => true,
            'width' => '10%',
        ),
        'PROJECT_STATUS' => array(
            'name' => 'project_status',
            'label' => 'LBL_PROJECT_STATUS',
            'enabled' => true,
            'default' => true,
            'width' => '10%',
        ),
        'START_DATE' => array(
            'name' => 'start_date',
            'label' => 'LBL_START_DATE',
            'enabled' => true,
            'default' => true,
            'width' => '10%',
        ),
        'END_DATE' => array(
            'name' => 'end_date',
            'label' => 'LBL_END_DATE',
            'enabled' => true,
            'default' => true,
            'width' => '10%',
        ),
        'ASSIGNED_USER_NAME' => array(
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
            'default' => true,
            'enabled' => true,
            'link' => true,
            'width' => '10%',
        ),
        'START_WORK_DATE' => array(
            'name' => 'start_work_date',
            'label' => 'LBL_START_WORK_DATE',
            'enabled' => true,
            'default' => false,
            'width' => '10%',
        ),
        'DATE_ENTERED' => array(
            'type' => 'datetime',
            'label' => 'LBL_DATE_ENTERED',
            'width' => '10%',
            'default' => false,
        ),
        'SALARY_FROM' => array(
            'name' => 'salary_from',
            'label' => 'LBL_SALARY_FROM',
            'related_fields' => array(
                0 => 'currency_id',
            ),
            'currency_field' => 'currency_id',
            'enabled' => true,
            'default' => false,
            'width' => '10%',
        ),
        'SALARY_TO' => array(
            'name' => 'salary_to',
            'label' => 'LBL_SALARY_TO',
            'related_fields' => array(
                0 => 'currency_id',
            ),
            'currency_field' => 'currency_id',
            'enabled' => true,
            'default' => false,
            'width' => '10%',
        ),
        'RECRUITMENT_CHANNELS' => array(
            'type' => 'multienum',
            'default' => false,
            'studio' => 'visible',
            'label' => 'LBL_RECRUITMENT_CHANNELS',
            'width' => '10%',
        ),
        'CREATED_BY_NAME' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_CREATED',
            'id' => 'CREATED_BY',
            'width' => '10%',
            'default' => false,
        ),
        'VACANCY' => array(
            'name' => 'vacancy',
            'label' => 'LBL_VACANCY',
            'enabled' => true,
            'default' => false,
            'width' => '10%',
        ),
        'EMPLOYEES_NUMBER' => array(
            'name' => 'employees_number',
            'label' => 'LBL_EMPLOYEES_NUMBER',
            'enabled' => true,
            'default' => false,
            'width' => '10%',
        ),
        'RECRUITMENT_TYPE' => array(
            'type' => 'enum',
            'default' => false,
            'studio' => 'visible',
            'label' => 'LBL_RECRUITMENT_TYPE',
            'width' => '10%',
        ),
        'DATE_MODIFIED' => array(
            'label' => 'LBL_DATE_MODIFIED',
            'enabled' => true,
            'default' => false,
            'name' => 'date_modified',
            'readonly' => true,
            'width' => '10%',
        ),
        'MODIFIED_BY_NAME' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_MODIFIED_NAME',
            'id' => 'MODIFIED_USER_ID',
            'width' => '10%',
            'default' => false,
        ),
    )
);
