<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}
$dictionary["onboardingoffboardingelements_offboardingtemplates"] = array(
   'true_relationship_type' => 'many-to-many',
   'from_studio' => true,
   'relationships' => array(
      'onboardingoffboardingelements_offboardingtemplates' => array(
         'lhs_module' => 'OnboardingOffboardingElements',
         'lhs_table' => 'onboardingoffboardingelements',
         'lhs_key' => 'id',
         'rhs_module' => 'OffboardingTemplates',
         'rhs_table' => 'offboardingtemplates',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'onboardingoffboardingelements_offboardingtemplates',
         'join_key_lhs' => 'onboardingoffboardingelements_id',
         'join_key_rhs' => 'offboardingtemplates_id',
      ),
   ),
   'table' => 'onboardingoffboardingelements_offboardingtemplates',
   'fields' => array(
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
         'name' => 'onboardingoffboardingelements_id',
         'type' => 'varchar',
         'len' => 36,
      ),
      array(
         'name' => 'offboardingtemplates_id',
         'type' => 'varchar',
         'len' => 36,
      ),
   ),
   'indices' => array(
      array(
         'name' => 'onboardingoffboardingelements_id_offboardingtemplates_id_spk',
         'type' => 'primary',
         'fields' => array(
            'id',
         ),
      ),
      array(
         'name' => 'onboardingoffboardingelements_id_alt',
         'type' => 'index',
         'fields' => array(
            'onboardingoffboardingelements_id',
         ),
      ),
      array(
         'name' => 'offboardingtemplates_id_alt',
         'type' => 'index',
         'fields' => array(
            'offboardingtemplates_id',
         ),
      ),
   ),
);
