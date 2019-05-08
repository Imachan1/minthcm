<?php

$dictionary['Improvements'] = array(
   'table' => 'improvements',
   'audited' => true,
   'inline_edit' => true,
   'duplicate_merge' => true,
   'fields' => array(
      "conclusions" => array(
         'name' => 'conclusions',
         'type' => 'link',
         'relationship' => 'conclusions_improvements',
         'source' => 'non-db',
         'module' => 'Conclusions',
         'bean_name' => 'Conclusions',
         'vname' => 'LBL_CONCLUSIONS',
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
VardefManager::createVardef('Improvements', 'Improvements', array( 'basic', 'assignable', 'security_groups' ));

$dictionary['Improvements']['fields']['name']['audited'] = true;