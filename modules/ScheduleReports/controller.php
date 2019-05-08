<?php

class ScheduleReportsController extends SugarController {

   /**
    * Function change view on Email Options
    */
   function action_config() {
      $this->view = "EmailOptions";
   }

   /**
    * Function started when we save changes in EmailOptions and this saves details in file 
    */
   public function action_saveEmailOptions() {
      $path = 'modules/ScheduleReports/pdf_email_cfg.php';
      $options = array();
      $options['email_template_id']['ScheduleReports'] = $_REQUEST['email_template_id'];
      $fp = sugar_fopen($path, 'w');
      fclose($fp);
      write_array_to_file('options', $options, $path);
      SugarApplication::redirect("index.php?module=ScheduleReports&action=index");
   }

}
