<?php

require_once 'include/NoticeGenerator.php';

/**
 * Class creates new logs and runs function which send e-mails with attachment. 
 */
class GeneratorEmails {

   /**
    * Function create new logs, new note with attachment.
    * @param object $bean, object must be ScheduleReports.
    * @return error log
    */
   public function generatePDF($bean) {
      $filename = "AdvancedReport_" . $bean->name . ".pdf"; //generate file name

      $log = new ScheduleReportsLogs(); //create new log
      $log->name = "$bean->name - $filename";
      $log->schedule_report_id = $bean->id;
      $log->status = 'ready';
      $log->execute_data = $GLOBALS['timedate']->nowDb();
      $log->save();

      $note = new Note(); //new note
      $note->filename = $filename;
      $note->file_mime_type = "application/pdf";
      $note->name = $filename;
      $note->parent_id = $bean->id;
      $note->parent_type = "ScheduleReports";
      $note->save();

      $note_id = $note->id;

      $path = "upload/" . $note_id; //path where file is save.

      require_once 'modules/KReports/controller.php';
      $report = new KReportsController(); //get controller Kreports. We need function action_export_to_pdf.
      $_REQUEST['record'] = $bean->kreport_id; //create new var becouse we must have id KReporter
      $report->action_export_to_pdf($bean->kreport_id, $path, $bean->template_id); //function create PDF file.

      $return = $this->sendEmail($note_id, $bean); //send email

      if ( file_exists($path) ) {//if pdf file exist -> change status in log
         $log->status = 'in progress';
         if ( $return == true ) { //if email was send
            $log->status = 'completed';
            global $timedate;
            $data_send = $timedate->nowDbDate();
            $bean->date_send = $data_send; //save data execute this job in schedulereport. Variable is nessesery in job function
            $bean->save();
         } elseif ( $return == "no file" ) {//if pdf wasn't created
            $log->status = 'failed';
         }
         $log->save();
      }
      return $return;
   }

   /**
    * @param string $attachments, id note with attachnemnt
    * @param object $bean
    * @return string|NULL|boolean
    */
   protected function sendEmail($attachments, $bean) {
      global $log;
      $db = DBManagerFactory::getInstance();

      $q = "SELECT COUNT(1) from email_templates where id = '{$bean->email_template_id}' and deleted=0";

      if ( empty($bean->email_template_id) ) {
         $log->fatal("[EV][ScheduleReports] File with e-mail templates config doesn't exist");
      } else if ( !$db->getOne($q) ) {
         $log->fatal("[EV][ScheduleReports] E-mail template doesn't exist. ID:'{$bean->email_template_id}'");
      } else {
         $emails = $this->loadRelatedUsersEmails($bean);

         if ( !empty($emails) ) {
            $send_to = array(
               'to' => $emails,
            );
            $attchs = !is_array($attachments) ? array( $attachments ) : $attachments;

            $eu = new NoticeEmailUtils();
            return $eu->sendEmail($bean->email_template_id, $send_to, 'ScheduleReports', $bean->id, null, null, null, null, null, $attchs);
         }
      }
      return false;
   }

   protected function loadRelatedUsersEmails($bean) {
      $emails = [];

      if ( $bean->load_relationship('users') ) {
         $users = $bean->users->getBeans();
         foreach ( $users as $user ) {
            if ( !empty($user->email1) ) {
               $emails[] = $user->email1;
            }
         }
      }

      return $emails;
   }

}
