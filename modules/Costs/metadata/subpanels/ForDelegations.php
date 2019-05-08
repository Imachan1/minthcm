<?php

// created: 2012-04-13 16:23:10
$subpanel_layout['list_fields'] = array(
   'name' => array(
      'vname' => 'LBL_NAME',
      'widget_class' => 'SubPanelDetailViewLink',
      'width' => '45%',
      'default' => true,
   ),
   'type' => array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'vname' => 'LBL_TYPE',
      'sortable' => true,
      'width' => '10%',
   ),
   'cost_date' => array(
      'type' => 'date',
      'vname' => 'LBL_COST_DATE',
      'width' => '10%',
      'default' => true,
   ),
   'cost_amount_usdollars' => array(
      'type' => 'currency',
      'vname' => 'LBL_COST_AMOUNT_BASE',
      'currency_format' => true,
      'width' => '10%',
      'default' => true,
   ),
   'cost_amount' => array(
      'type' => 'currency',
      'vname' => 'LBL_COST_AMOUNT',
      'currency_format' => true,
      'width' => '10%',
      'default' => false,
   ),
   'edit_button' => array(
      'widget_class' => 'SubPanelEditButton',
      'module' => 'Costs',
      'width' => '4%',
      'default' => true,
   ),
   'currency_id' => array(
      'usage' => 'query_only',
   ),
);
