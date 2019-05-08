<?php

$popupMeta = array(
   'moduleMain' => 'Onboardings',
   'varName' => 'Onboardings',
   'orderBy' => 'onboardings.name',
   'whereClauses' => array(
      'name' => 'onboardings.name',
      'date_start' => 'onboardings.date_start',
      'status' => 'onboardings.status',
      'onboardingtemplate_name' => 'onboardings.onboardingtemplate_name',
      'assigned_user_name' => 'onboardings.assigned_user_name',
   ),
   'searchInputs' => array(
      'name',
      'status',
      'date_start',
      'onboardingtemplate_name',
      'assigned_user_name',
   ),
   'searchdefs' => array(
      'name' =>
      array(
         'name' => 'name',
         'width' => '10%',
      ),
      'date_start' =>
      array(
         'type' => 'datetimecombo',
         'label' => 'LBL_DATE_START',
         'width' => '10%',
         'name' => 'date_start',
      ),
      'status' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_STATUS',
         'width' => '10%',
         'name' => 'status',
      ),
      'onboardingtemplate_name' =>
      array(
         'type' => 'relate',
         'link' => true,
         'label' => 'LBL_ONBOARDINGTEMPLATE_NAME',
         'id' => 'ONBOARDINGTEMPLATE_ID',
         'width' => '10%',
         'name' => 'onboardingtemplate_name',
      ),
      'employee_name' =>
      array(
         'name' => 'employee_id',
         'label' => 'LBL_EMPLOYEE_NAME',
         'type' => 'enum',
         'function' =>
         array(
            'name' => 'get_user_array',
            'params' =>
            array(
               false,
            ),
         ),
         'default' => true,
         'width' => '10%',
      ),
      'assigned_user_name' =>
      array(
         'link' => true,
         'type' => 'relate',
         'label' => 'LBL_ASSIGNED_TO_NAME',
         'id' => 'ASSIGNED_USER_ID',
         'width' => '10%',
         'name' => 'assigned_user_name',
      ),
   ),
);
