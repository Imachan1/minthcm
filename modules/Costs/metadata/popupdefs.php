<?php

$popupMeta = array(
   'moduleMain' => 'Costs',
   'varName' => 'Costs',
   'orderBy' => 'costs.name',
   'whereClauses' => array(
      'type' => 'costs.type',
      'cost_amount' => 'costs.cost_amount',
      'cost_date' => 'costs.cost_date',
   ),
   'searchInputs' => array(
      4 => 'type',
      5 => 'cost_amount',
      6 => 'cost_date',
   ),
   'searchdefs' => array(
      'type' => array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_TYPE',
         'sortable' => false,
         'width' => '10%',
         'name' => 'type',
      ),
      'cost_amount' => array(
         'type' => 'currency',
         'label' => 'LBL_COST_AMOUNT',
         'currency_format' => true,
         'width' => '10%',
         'name' => 'cost_amount',
      ),
      'cost_date' => array(
         'type' => 'currency',
         'label' => 'LBL_COST_DATE',
         'currency_format' => true,
         'width' => '10%',
         'name' => 'cost_date',
      ),
   ),
   'listviewdefs' => array(
      'NAME' => array(
         'type' => 'name',
         'link' => true,
         'label' => 'LBL_NAME',
         'width' => '10%',
         'default' => true,
      ),
      'COST_DATE' => array(
         'type' => 'date',
         'label' => 'LBL_COST_DATE',
         'width' => '10%',
         'default' => true,
      ),
      'TYPE' => array(
         'type' => 'enum',
         'default' => true,
         'studio' => 'visible',
         'label' => 'LBL_TYPE',
         'sortable' => true,
         'width' => '10%',
         'name' => 'type',
      ),
      'COST_CITY' => array(
         'type' => 'varchar',
         'label' => 'LBL_COST_CITY',
         'width' => '10%',
         'default' => true,
      ),
      'COST_AMOUNT_USDOLLAR' => array(
         'type' => 'currency',
         'label' => 'LBL_COST_AMOUNT_USDOLLAR',
         'currency_format' => true,
         'width' => '10%',
         'default' => true,
         'name' => 'cost_amount',
      ),
   ),
);
