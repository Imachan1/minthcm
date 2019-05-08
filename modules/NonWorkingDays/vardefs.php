<?php

$dictionary['NonWorkingDays'] = array(
   'table' => 'nonworkingdays',
   'audited' => false,
   'duplicate_merge' => false,
   'fields' => array(
      'date' => array(
         'name' => 'date',
         'vname' => 'LBL_DATE',
         'type' => 'date',
         'audited' => true,
         'comment' => '',
         'importable' => 'required',
         'enable_range_search' => true,
         'options' => 'date_range_search_dom',
         'required' => true,
      ),
      'week_day' => array(
         'name' => 'week_day',
         'vname' => 'LBL_WEEK_DAY',
         'type' => 'enum',
         'len' => 255,
         'unified_search' => false,
         'required' => true,
         'importable' => 'required',
         'options' => 'week_days_list',
         'duplicate_merge' => 'disabled',
         'merge_filter' => 'disabled'
      ),
   ),
   'indices' => array(),
   'relationships' => array(),
   'optimistic_locking' => true,
   'unified_search' => false
);
if ( !class_exists('VardefManager') ) {
   require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('NonWorkingDays', 'NonWorkingDays', array( 'basic' ));
