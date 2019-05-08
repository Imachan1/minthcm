<?php

class executeViewToolsQueue {

   public $db;

   const TABLE_NAME = 'view_tools_queue';
   const PACKAGE_COUNT = 20;

   public function __construct() {
      $this->db = DBManagerFactory::getInstance();
   }

   public function run() {
      $sql = "SELECT * FROM " . self::TABLE_NAME . " ORDER BY date_modified LIMIT " . self::PACKAGE_COUNT;
      $queue = new ViewToolsQueue();
      $sql_result = $this->db->query($sql);
      while ( $row = $this->db->fetchByAssoc($sql_result) ) {
         $focus = BeanFactory::getBean($row['module_name'], $row['record_id']);
         if ( $focus && !empty($focus->id) ) {
            $focus->save();
         }
         $queue->delete($row['id']);
      }
   }

}
