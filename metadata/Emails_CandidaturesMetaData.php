<?php

$dictionary["candidatures_emails"] = array(
   'relationships' =>
   array(
      'candidatures_emails' =>
      array(
         'lhs_module' => 'Candidatures',
         'lhs_table' => 'candidatures',
         'lhs_key' => 'id',
         'rhs_module' => 'Emails',
         'rhs_table' => 'emails',
         'rhs_key' => 'parent_id',
         'relationship_type' => 'one-to-many',
         'relationship_role_column' => 'parent_type',
         'relationship_role_column_value' => 'Candidatures',
      ),
   ),
);
