<?php

$dictionary['Conclusions'] = array(
   'table' => 'conclusions',
   'audited' => true,
   'inline_edit' => true,
   'duplicate_merge' => true,
   'fields' => array(
      "improvements" => array( 
         'name' => 'improvements',
         'type' => 'link',
         'relationship' => 'conclusions_improvements',
         'source' => 'non-db',
         'module' => 'Improvements',
         'bean_name' => 'Improvements',
         'vname' => 'LBL_IMPROVEMENTS',
      ),
      "problems" => array( 
         'name' => 'problems',
         'type' => 'link',
         'relationship' => 'conclusions_problems',
         'source' => 'non-db',
         'module' => 'Problems',
         'bean_name' => 'Problems',
         'vname' => 'LBL_PROBLEMS', 
      ),
      "meetings" => array(
         'name' => 'meetings',
         'type' => 'link',
         'relationship' => 'conclusions_meetings',
         'source' => 'non-db',
         'module' => 'Meetings',
         'bean_name' => 'Meeting',
         'vname' => 'LBL_MEETINGS',
         'id_name' => 'meeting_id',
      ),
      "meeting_name" => array(
         'name' => 'meeting_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_MEETING_NAME',
         'save' => true,
         'id_name' => 'meeting_id',
         'link' => 'meetings',
         'module' => 'Meetings',
         'table' => 'meetings',
         'rname' => 'name',
         'audited' => true,
         'importable' => true,
         'reportable' => true,
         'massupdate' => true,
         'duplicate_merge' => 'enabled',
      ),
      "meeting_id" => array(
         'name' => 'meeting_id',
         'relationship' => 'conclusions_meetings',
         'type' => 'id',
         'vname' => 'LBL_MEETING_ID',
         'audited' => false,
         'reportable' => false,
      ),
   ),
   'relationships' => array(
      "conclusions_meetings" => array(
         'lhs_module' => 'Meetings',
         'lhs_table' => 'meetings',
         'lhs_key' => 'id',
         'rhs_module' => 'Conclusions',
         'rhs_table' => 'conclusions',
         'rhs_key' => 'meeting_id',
         'relationship_type' => 'one-to-many',
      ),
   ),
   'optimistic_locking' => true,
   'unified_search' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('Conclusions', 'Conclusions', array( 'basic', 'assignable', 'security_groups' ));

$dictionary['Conclusions']['fields']['name']['audited'] = true;