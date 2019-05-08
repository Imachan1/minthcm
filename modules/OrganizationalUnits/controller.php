<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

class OrganizationalUnitsController extends SugarController {

   public function getActiveUsers($organizational_units_ids) {
      global $db;
      $results = array();
      $sql = "SELECT id FROM users WHERE status='Active' AND deleted = 0";
      if ( !empty($organizational_units_ids) ) {
         $sql .= " AND organizationalunit_id IN ('" . implode('\',\'', $organizational_units_ids) . "')";
      }
      $result = $db->query($sql);
      while ( $row = $db->fetchByAssoc($result) ) {
         $results[] = $row['id'];
      }
      return $results;
   }

}

