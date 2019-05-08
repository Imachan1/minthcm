<?php

$dictionary["organizationalunits_employees"] = array(
   'true_relationship_type' => 'one-to-many',
   'relationships' =>
   array(
      "organizationalunits_employees" => array(
         'lhs_module' => 'OrganizationalUnits',
         'lhs_table' => 'organizationalunits',
         'lhs_key' => 'id',
         'rhs_module' => 'Employees',
         'rhs_table' => 'users',
         'rhs_key' => 'organizationalunit_id',
         'relationship_type' => 'one-to-many',
      ),
   ),
);

