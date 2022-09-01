<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Candidates';
$ESListViewDefs[$module_name] = array(
    'columns' => array(
        'NAME' => array(
            'label' => 'LBL_NAME',
            'link' => true,
            'orderBy' => 'last_name',
            'default' => true,
            'related_fields' => array(
                0 => 'first_name',
                1 => 'last_name',
                2 => 'salutation',
            ),
            'width' => '10%',
        ),
        'EMAIL1' => array(
            'width' => '15%',
            'label' => 'LBL_EMAIL_ADDRESS',
            'sortable' => false,
            'link' => true,
            'customCode' => '{$EMAIL1_LINK}',
            'default' => true,
        ),
        'PHONE_MOBILE' => array(
            'label' => 'LBL_MOBILE_PHONE',
            'width' => '10%',
            'default' => false,
        ),
        'RECR_CONTACT_AGREE' => array(
            'width' => '5%',
            'label' => 'LBL_RECR_CONTACT_AGREE_SHORT',
            'link' => false,
            'default' => false,

        ),
        'POTENTIAL' => array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_POTENTIAL',
            'width' => '10%',
        ),
        'BIRTHDATE' => array(
            'label' => 'LBL_BIRTHDATE',
            'type' => 'date',
            'width' => '10%',
            'default' => false,
        ),
        'RELOCATION' => array(
            'label' => 'LBL_RELOCATION',
            'type' => 'bool',
            'width' => '10%',
            'default' => false,
        ),
        'LBL_LAST_TIME_CONTACT' => array(
            'label' => 'LBL_LAST_TIME_CONTACT',
            'width' => '10%',
            'default' => false,
        ),
        'LBL_DATE_PLANNED_CONTACT' => array(
            'label' => 'LBL_DATE_PLANNED_CONTACT',
            'width' => '10%',
            'default' => false,
        ),
        'DATE_ENTERED' => array(
            'label' => 'LBL_DATE_ENTERED',
            'width' => '10%',
            'default' => false,
        ),
        'ASSIGNED_USER_NAME' => array(
            'link' => true,
            'type' => 'relate',
            'label' => 'LBL_ASSIGNED_TO_NAME',
            'id' => 'ASSIGNED_USER_ID',
            'width' => '10%',
            'default' => true,
        ),
        'EMPLOYEE_NAME' => array(
            'width' => '9%',
            'label' => 'LBL_EMPLOYEE_NAME',
            'module' => 'Employees',
            'id' => 'EMPLOYEE_ID',
            'default' => true,
            'link' => true
        ),
        'SKYPE' => array(
            'label' => 'LBL_SKYPE',
            'type' => 'varchar',
            'width' => '10%',
            'default' => false,
        ),
        'GOLDENLINE' => array(
            'label' => 'LBL_GOLDENLINE',
            'type' => 'url',
            'width' => '10%',
            'default' => false,
        ),
        'LAST_NAME' => array(
            'type' => 'varchar',
            'label' => 'LBL_LAST_NAME',
            'width' => '10%',
            'default' => false,
        ),
        'LINKEDIN' => array(
            'label' => 'LBL_LINKEDIN_ACCOUNT',
            'type' => 'url',
            'width' => '10%',
            'default' => false,
        ),
        'FIRST_NAME' => array(
            'type' => 'varchar',
            'label' => 'LBL_FIRST_NAME',
            'width' => '10%',
            'default' => false,
        ),
        'CREATED_BY_NAME' => array(
            'label' => 'LBL_CREATED',
            'width' => '10%',
            'default' => false,
        ),
        'PRIMARY_ADDRESS_STREET' => array(
            'type' => 'text',
            'label' => 'LBL_PRIMARY_STREET',
            'sortable' => false,
            'width' => '10%',
            'default' => false,
        ),
        'FACEBOOK' => array(
            'name' => 'FACEBOOK',
            'label' => 'LBL_FACEBOOK',
            'default' => false,
            'type' => 'url',
            'width' => '10%',
        ),
        'PRIMARY_ADDRESS_CITY' => array(
            'type' => 'varchar',
            'label' => 'LBL_PRIMARY_ADDRESS_CITY',
            'width' => '10%',
            'default' => true,
        ),
        'PRIMARY_ADDRESS_STATE' => array(
            'type' => 'varchar',
            'label' => 'LBL_PRIMARY_ADDRESS_STATE',
            'width' => '10%',
            'default' => false,
        ),
        'PRIMARY_ADDRESS_COUNTRY' => array(
            'type' => 'varchar',
            'default' => false,
            'label' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
            'width' => '10%',
        ),
        'PRIMARY_ADDRESS_POSTALCODE' => array(
            'type' => 'varchar',
            'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
            'width' => '10%',
            'default' => false,
        ),
    )
);
