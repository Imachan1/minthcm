<?php

class ViewToolsQueue {

   public $db;
   public $id;
   public $date_modified;
   public $module_name;
   public $record_id;

   const TABLE_NAME = 'view_tools_queue';

   public function __construct() {
      $this->db = DBManagerFactory::getInstance();
   }

   public function save() {
      if ( !empty($this->id) ) {
         $sql = "UPDATE " . self::TABLE_NAME . " SET id='{$this->id}', module_name='{$this->module_name}', record_id='{$this->record_id}', date_modified='{$GLOBALS['timedate']->nowDb()}' WHERE id='{$this->id}'";
      } else {
         $this->id = create_guid();
         $sql = "INSERT INTO " . self::TABLE_NAME . " (id, date_modified, module_name, record_id) VALUES ('" . $this->id . "','" . $GLOBALS['timedate']->nowDb() . "','{$this->module_name}','{$this->record_id}')";
      }
      if ( $this->db->query($sql) ) {
         return $this->id;
      } else {
         return false;
      }
   }

   public function delete($id) {
      return $this->db->query("DELETE FROM " . self::TABLE_NAME . " WHERE id='{$id}'");
   }

   public function retrieve($id) {
      if ( !empty($id) ) {
         $sql = "SELECT * FROM " . self::TABLE_NAME . " WHERE id='{$id}'";
         $row = $this->db->fetchOne($sql);
         foreach ( $row as $field_name => $value ) {
            $this->$field_name = $value;
         }
         return $this;
      } else {
         return false;
      }
   }

}
