<?php

class SequenceUtils {

   protected const table_name = 'sequences';

   public function __construct() {
      $this->tryDatabase();
   }

   private function tryDatabase() {
      $this->db_name = $GLOBALS['sugar_config']['dbconfig']['db_name'];
      $this->db = DBManagerFactory::getInstance();
      $query = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '" . $this->db_name .
         "' AND table_name = '" . static::table_name . "'";
      $row = $this->db->fetchByAssoc($this->db->query($query));
      $table_exists = $row['COUNT(*)'];

      if ( !$table_exists ) {
         $this->db->query("CREATE TABLE `" . $this->db_name . "`.`" . static::table_name .
            "` (`name` VARCHAR( 255 ) NOT NULL ,`value` INT NOT NULL DEFAULT  '1')");
      }
   }

   public function getNext($name) {
      $query = "SELECT * FROM `" . static::table_name . "` WHERE `name` = '" . $name . "'";
      $result = $this->db->query($query);
      $row = $this->db->fetchByAssoc($result);

      if ( $result->num_rows != 0 ) {
         $next = $row['value'] + 1;
         $this->db->query("UPDATE `" . $this->db_name . "`.`" . static::table_name .
            "` SET `value` = '" . $next . "' WHERE `" . static::table_name . "`.`name` = '" . $name . "' LIMIT 1 ;");
      } else {
         $next = 1;
         $this->db->query("INSERT INTO `" . $this->db_name . "`.`" . static::table_name .
            "` VALUES ('" . $name . "', '1');");
      }
      return $next;
   }

   public function getCurrent($name) {
      $query = "SELECT * FROM `" . static::table_name . "` WHERE `name` = '" . $name . "'";
      $result = $this->db->query($query);
      $row = $this->db->fetchByAssoc($result);
      $current = $row['value'];
      if ( $result->num_rows == 0 ) {
         $current = 1;
         $this->db->query("INSERT INTO `" . $this->db_name . "`.`" . static::table_name .
            "` VALUES ('" . $name . "', '1');");
      }
      return $current;
   }

   public function setValue($name, $value) {
      $query = "SELECT * FROM `" . static::table_name . "` WHERE `name` = '" . $name . "'";
      $result = $this->db->query($query);
      if ( $result->num_rows == 0 ) {
         $this->db->query("INSERT INTO `" . $this->db_name . "`.`" . static::table_name .
            "` VALUES ('" . $name . "', '" . $value . "');");
      } else {
         $this->db->query("UPDATE `" . $this->db_name . "`.`" . static::table_name .
            "` SET `value` = '" . $value . "' WHERE `" . static::table_name . "`.`name` = '" . $name . "' LIMIT 1 ;");
      }
   }

   public function decrement($name) {
      $query = "SELECT * FROM `" . static::table_name . "` WHERE `name` = '" . $name . "'";
      $result = $this->db->query($query);
      if ( $result->num_rows == 0 ) {
         $this->db->query("INSERT INTO `" . $this->db_name . "`.`" . static::table_name .
            "` VALUES ('" . $name . "', '0');");
      } else {
         $this->db->query("UPDATE `" . $this->db_name . "`.`" . static::table_name .
            "` SET `value` = `value`-1 WHERE `" . static::table_name . "`.`name` = '" . $name . "' LIMIT 1 ;");
      }
   }

}
