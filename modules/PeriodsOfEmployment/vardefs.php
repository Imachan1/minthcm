<?php

$dictionary['PeriodsOfEmployment'] = array(
   'table' => 'periodsofemployment',
   'audited' => true,
   'inline_edit' => true,
   'duplicate_merge' => true,
   'fields' => array(
      'name' =>
      array(
         'name' => 'name',
         'vname' => 'LBL_NAME',
         'type' => 'name',
         'link' => true,
         'dbType' => 'varchar',
         'len' => '255',
         'unified_search' => false,
         'full_text_search' =>
         array(
            'boost' => 3,
         ),
         'required' => true,
         'importable' => 'required',
         'duplicate_merge' => 'enabled',
         'merge_filter' => 'disabled',
         'massupdate' => 0,
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'duplicate_merge_dom_value' => '1',
         'audited' => true,
         'inline_edit' => true,
         'reportable' => true,
         'size' => '20',
         'vt_readonly' => 'equals(1,1)',
      ),
      'period_ending_date' =>
      array(
         'name' => 'period_ending_date',
         'vname' => 'LBL_PERIOD_ENDING_DATE',
         'type' => 'date',
         'massupdate' => '1',
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'inline_edit' => true,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'size' => '20',
         'enable_range_search' => true,
         'dbType' => 'datetime',
         'options' => 'date_range_search_dom',
      ),
      'period_starting_date' =>
      array(
         'name' => 'period_starting_date',
         'vname' => 'LBL_PERIOD_STARTING_DATE',
         'type' => 'date',
         'massupdate' => '1',
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'inline_edit' => true,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'size' => '20',
         'enable_range_search' => true,
         'dbType' => 'datetime',
         'validation' => array( 'type' => 'isbefore', 'compareto' => 'period_ending_date' ),
         'options' => 'date_range_search_dom',
      ),
      "contracts" => array(
         'name' => 'contracts',
         'type' => 'link',
         'relationship' => 'periodsofemployment_contracts',
         'source' => 'non-db',
         'module' => 'Contracts',
         'bean_name' => 'Contracts',
         'side' => 'right',
         'vname' => 'LBL_CONTRACTS',
      ),
   ),
   'relationships' => array(
   ),
   'optimistic_locking' => true,
   'unified_search' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('PeriodsOfEmployment', 'PeriodsOfEmployment', array(
   'basic',
   'assignable',
   'security_groups',
   'employee_related'
        )
);
