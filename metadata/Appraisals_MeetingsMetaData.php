<?php

// created: 2018-10-11 11:45:12
$dictionary["appraisals_meetings"] = array(
   'true_relationship_type' => 'many-to-many',
   'relationships' =>
   array(
      'appraisals_meetings' =>
      array(
         'lhs_module' => 'Appraisals',
         'lhs_table' => 'appraisals',
         'lhs_key' => 'id',
         'rhs_module' => 'Meetings',
         'rhs_table' => 'meetings',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'appraisals_meetings',
         'join_key_lhs' => 'appraisal_id',
         'join_key_rhs' => 'meeting_id',
      ),
   ),
   'table' => 'appraisals_meetings',
   'fields' =>
   array(
      array(
         'name' => 'id',
         'type' => 'varchar',
         'len' => 36,
      ),
      array(
         'name' => 'date_modified',
         'type' => 'datetime',
      ),
      array(
         'name' => 'deleted',
         'type' => 'bool',
         'len' => '1',
         'default' => '0',
         'required' => true,
      ),
      array(
         'name' => 'appraisal_id',
         'type' => 'varchar',
         'len' => 36,
      ),
      array(
         'name' => 'meeting_id',
         'type' => 'varchar',
         'len' => 36,
      ),
   ),
   'indices' =>
   array(
      array(
         'name' => 'appraisals_meetingsspk',
         'type' => 'primary',
         'fields' =>
         array(
            'id',
         ),
      ),
      array(
         'name' => 'appraisals_meetings_alt',
         'type' => 'alternate_key',
         'fields' =>
         array(
            'appraisal_id',
            'meeting_id',
         ),
      ),
   ),
);
