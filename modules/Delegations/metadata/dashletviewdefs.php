<?php

$dashletData['DelegationsDashlet']['searchFields'] = array(
   'date_entered' => array(
      'default' => '',
   ),
   'date_modified' => array(
      'default' => '',
   ),
   'start_date' => array(
      'default' => '',
   ),
   'end_date' => array(
      'default' => '',
   ),
   'purpose' => array(
      'default' => '',
   ),
);
$dashletData['DelegationsDashlet']['columns'] = array(
   'name' => array(
      'width' => '40%',
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => true,
      'name' => 'name',
   ),
   'assigned_user_name' => array(
      'width' => '8%',
      'label' => 'LBL_LIST_ASSIGNED_USER',
      'name' => 'assigned_user_name',
      'default' => true,
   ),
   'purpose' => array(
      'type' => 'varchar',
      'default' => true,
      'label' => 'LBL_PURPOSE',
      'width' => '10%',
   ),
   'total_expenses' => array(
      'type' => 'currency',
      'label' => 'LBL_TOTAL_EXPENSES',
      'currency_format' => true,
      'width' => '10%',
      'default' => true,
      'related_fields' => array(
         'regimen_value',
         'accommodation_value',
         'start_date',
         'end_date',
         'obtained_sum',
      ),
   ),
   'date_modified' => array(
      'width' => '15%',
      'label' => 'LBL_DATE_MODIFIED',
      'name' => 'date_modified',
      'default' => false,
   ),
   'date_entered' => array(
      'width' => '15%',
      'label' => 'LBL_DATE_ENTERED',
      'default' => false,
      'name' => 'date_entered',
   ),
   'end_date' => array(
      'type' => 'datetimecombo',
      'label' => 'LBL_END_DATE',
      'width' => '10%',
      'default' => false,
   ),
   'start_date' => array(
      'type' => 'datetimecombo',
      'label' => 'LBL_START_DATE',
      'width' => '10%',
      'default' => false,
   ),
   'regiments_usdollar' => array(
      'type' => 'currency',
      'label' => 'LBL_REGIMENTS',
      'currency_format' => true,
      'width' => '10%',
      'default' => false,
      'related_fields' => array(
         'regimen_value',
         'start_date',
         'end_date',
      ),
   ),
   'transport_cost_usdollar' => array(
      'type' => 'currency',
      'label' => 'LBL_TRANSPORT_COST',
      'currency_format' => true,
      'width' => '10%',
      'default' => false,
   ),
   'total_accommodation_usdollar' => array(
      'type' => 'currency',
      'label' => 'LBL_TOTAL_ACCOMMODATION',
      'currency_format' => true,
      'width' => '10%',
      'default' => false,
   ),
   'accommodation_lump_sum' => array(
      'type' => 'currency',
      'label' => 'LBL_ACCOMMODATION_LUMP_SUM',
      'currency_format' => true,
      'width' => '10%',
      'default' => false,
      'related_fields' => array(
         'accommodation_value',
         'start_date',
         'end_date',
      ),
   ),
   'other_usdollar' => array(
      'type' => 'currency',
      'label' => 'LBL_OTHER',
      'currency_format' => true,
      'width' => '10%',
      'default' => false,
   ),
   'obtained_sum_usdollar' => array(
      'type' => 'currency',
      'label' => 'LBL_OBTAINED_SUM',
      'currency_format' => true,
      'width' => '10%',
      'default' => false,
   ),
   'payoff_sum_usdollar' => array(
      'type' => 'currency',
      'label' => 'LBL_PAYOFF_SUM',
      'currency_format' => true,
      'width' => '10%',
      'default' => false,
      'related_fields' => array(
         'regimen_value',
         'accommodation_value',
         'start_date',
         'end_date',
         'obtained_sum',
      ),
   ),
   'return_sum_usdollar' => array(
      'type' => 'currency',
      'label' => 'LBL_RETURN_SUM',
      'currency_format' => true,
      'width' => '10%',
      'default' => false,
      'related_fields' => array(
         'regimen_value',
         'accommodation_value',
         'start_date',
         'end_date',
         'obtained_sum',
      ),
   ),
);
