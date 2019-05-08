<?php

// created: 2018-10-13 15:14:30 
$dictionary["organizationalunits_news"] = array(
   'true_relationship_type' => 'many-to-many',
   'relationships' =>
   array(
      'organizationalunits_news' =>
      array(
         'lhs_module' => 'OrganizationalUnits',
         'lhs_table' => 'organizationalunits',
         'lhs_key' => 'id',
         'rhs_module' => 'News',
         'rhs_table' => 'news',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'organizationalunits_news',
         'join_key_lhs' => 'organizationalunit_id',
         'join_key_rhs' => 'news_id',
      ),
   ),
   'table' => 'organizationalunits_news',
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
         'name' => 'organizationalunit_id',
         'type' => 'varchar',
         'len' => 36,
      ),
      array(
         'name' => 'news_id',
         'type' => 'varchar',
         'len' => 36,
      ),
   ),
   'indices' =>
   array(
      array(
         'name' => 'id_index',
         'type' => 'primary',
         'fields' =>
         array(
            'id',
         ),
      ),
      array(
         'name' => 'organizationalunits_news_id',
         'type' => 'alternate_key',
         'fields' =>
         array(
            'organizationalunit_id',
            'news_id',
         ),
      ),
   ),
);
