<?php


$dictionary["positions_employees"] = array(
   'true_relationship_type' => 'one-to-many',
   'relationships' =>
   array(
      "positions_employees" => array( 
         'lhs_module' => 'Positions', 
         'lhs_table' => 'positions', 
         'lhs_key' => 'id', 
         'rhs_module' => 'Employees', 
         'rhs_table' => 'users', 
         'rhs_key' => 'position_id', 
         'relationship_type' => 'one-to-many', 
      ),
   ),
);

