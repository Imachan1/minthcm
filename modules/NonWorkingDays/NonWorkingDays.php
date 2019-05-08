<?php

class NonWorkingDays extends Basic {

   public $new_schema = true;
   public $module_dir = 'NonWorkingDays';
   public $object_name = 'NonWorkingDays';
   public $table_name = 'nonworkingdays';
   public $real_table_name = 'nonworkingdays';
   public $importable = false;
   public $disable_row_level_security = true;
   public $id;
   public $name;
   public $date_entered;
   public $date_modified;
   public $modified_user_id;
   public $modified_by_name;
   public $created_by;
   public $created_by_name;
   public $deleted;
   public $date;
   public $week_day;

   public function bean_implements($interface) {
      switch ( $interface ) {
         case 'ACL': return true;
         default: return false;
      }
   }

   public function save($notify = false) {
      global $timedate, $app_list_strings;
      $this->week_day = $this->getWeekDayFromDate($timedate->to_db_date($this->date, false));
      $this->name = $timedate->to_db_date($this->date, false) . ' ' . $app_list_strings['week_days_list'][$this->week_day];

      return parent::save($notify);
   }

   public function getWeekDayFromDate($date) {
      return date('w', strtotime($date));
   }

}
