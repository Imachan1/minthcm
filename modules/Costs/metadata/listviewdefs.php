<?php

$module_name = 'Costs';
$listViewDefs [$module_name] = array(
   'NAME' => array(
      'width' => '20%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'ASSIGNED_USER_NAME' => array(
      'width' => '9%',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'module' => 'Employees',
      'id' => 'ASSIGNED_USER_ID',
      'default' => true,
   ),
   'DELEGATION_NAME' => array(
      'type' => 'relate',
      'link' => 'costs_delegations',
      'related_fields' => array( 'delegation_id', ),
      'label' => 'LBL_DELEGATION_NAME',
      'width' => '10%',
      'default' => true,
   ),
   'TRANSPORTATION_NAME' => array(
      'type' => 'relate',
      'related_fields' => array( 'transportation_id', ),
      'link' => 'transportations',
      'label' => 'LBL_TRANSPORTATION_NAME',
      'width' => '10%',
      'default' => true,
   ),
   'TYPE' => array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_TYPE',
      'sortable' => false,
      'width' => '10%',
   ),
   'COST_AMOUNT' => array(
      'type' => 'currency',
      'label' => 'LBL_COST_AMOUNT',
      'currency_format' => true,
      'width' => '10%',
      'default' => false,
   ),
   'COST_AMOUNT_USDOLLARS' => array(
      'type' => 'currency',
      //'label' => 'LBL_COST_AMOUNT_BASE',
      'label' => translate('LBL_COST_AMOUNT', $module_name) . ' (' . $this->ss->_tpl_vars['CURRENCY_SYMBOL'] . ')',
      'currency_format' => true,
      'width' => '10%',
      'default' => true,
   ),
   'COST_CITY' => array(
      'type' => 'varchar',
      'label' => 'LBL_COST_CITY',
      'width' => '10%',
      'default' => true,
   ),
   'COST_DATE' => array(
      'type' => 'date',
      'label' => 'LBL_COST_DATE',
      'width' => '10%',
      'default' => true,
   ),
);
