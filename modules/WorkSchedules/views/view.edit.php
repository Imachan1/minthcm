<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.edit.php');

class WorkSchedulesViewEdit extends ViewEdit {

   private function assignStrings() {
      global $app_list_strings, $mod_strings;
      $this->ss->assign('APPLIST', $app_list_strings);
      $this->ss->assign('MOD', $mod_strings);
   }

   private function assignRepeatInterval() {
      $repeat_intervals = array();
      for ( $i = 1; $i <= 30; $i++ ) {
         $repeat_intervals[$i] = $i;
      }
      $this->ss->assign("repeat_intervals", $repeat_intervals);
   }

   private function assignRepeatIntervalJS() {
      global $app_list_strings;
      $repeat_intervals_json = 'app_list_strings_repeat_intervals = {';
      foreach ( $app_list_strings['repeat_intervals'] as $k => $v ) {
         $repeat_intervals_json .= "'$k':'$v',";
      }
      $repeat_intervals_json .= '};';
      $this->ss->assign('ALSRI', $repeat_intervals_json);
   }

   private function assignDaysOfWeek() {
      global $current_user, $app_list_strings;
      $fdow = $current_user->get_first_day_of_week();
      $dow = array();
      for ( $i = $fdow; $i < $fdow + 7; $i++ ) {
         $day_index = $i % 7;
         $dow[] = array(
            "index" => $day_index,
            "label" => $app_list_strings['dom_cal_day_short'][$day_index + 1],
         );
      }
      $this->ss->assign("dow", $dow);
   }

   private function assignShowEditAllRecurrences() {
      $edit = (isset($_REQUEST['show_edit_all_recurrences']) && $_REQUEST['show_edit_all_recurrences']);
      $r = !$edit && $this->bean->repeat_type ? 1 : 0;
      $this->ss->assign('show_edit_all_recurrences', $r);
   }

   public function preDisplay() {

      $this->assignStrings();
      $this->assignRepeatInterval();
      $this->assignRepeatIntervalJS();
      $this->assignDaysOfWeek();
      $this->assignShowEditAllRecurrences();

      parent::preDisplay();
   }

   public function display() {
      global $current_user;
      $this->ev->ss->assign('CURRENT_USER_IS_ADMIN', is_admin($current_user));
      $this->ev->ss->assign('REDIRECTED_FROM_CALENDAR', 0);
      if ( isset($_GET['redirected_from_calendar']) ) {
         $this->ev->ss->assign('REDIRECTED_FROM_CALENDAR', 1);
      }
      if ( isset($_GET['return_module']) ) {
         $this->ev->ss->assign('RETURN_MODULE', $_GET['return_module']);
      }
      return parent::display();
   }

}
