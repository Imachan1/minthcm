<?php

$dictionary["recruitments_emails"] = array(
   'relationships' =>
   array(
      'recruitments_emails' =>
      array(
         'lhs_module' => 'Recruitments',
         'lhs_table' => 'recruitments',
         'lhs_key' => 'id',
         'rhs_module' => 'Emails',
         'rhs_table' => 'emails',
         'rhs_key' => 'parent_id',
         'relationship_type' => 'one-to-many',
         'relationship_role_column' => 'parent_type',
         'relationship_role_column_value' => 'Recruitments',
      ),
   ),
);
