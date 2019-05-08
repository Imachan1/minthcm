<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}
$dictionary["onboardingoffboardingelements_onboardingtemplates"] = array(
   'true_relationship_type' => 'many-to-many',
   'from_studio' => true,
   'relationships' => array(
      'onboardingoffboardingelements_onboardingtemplates' => array(
         'lhs_module' => 'OnboardingOffboardingElements',
         'lhs_table' => 'onboardingoffboardingelements',
         'lhs_key' => 'id',
         'rhs_module' => 'OnboardingTemplates',
         'rhs_table' => 'onboardingtemplates',
         'rhs_key' => 'id',
         'relationship_type' => 'many-to-many',
         'join_table' => 'onboardingoffboardingelements_onboardingtemplates',
         'join_key_lhs' => 'onboardingoffboardingelements_id',
         'join_key_rhs' => 'onboardingtemplates_id',
      ),
   ),
   'table' => 'onboardingoffboardingelements_onboardingtemplates',
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
         'name' => 'onboardingtemplates_id',
         'type' => 'varchar',
         'len' => 36,
      ),
   ),
   'indices' => array(
      array(
         'name' => 'onboardingoffboardingelements_id_onboardingtemplates_id_spk',
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
         'name' => 'onboardingtemplates_id_alt',
         'type' => 'index',
         'fields' => array(
            'onboardingtemplates_id',
         ),
      ),
   ),
);
