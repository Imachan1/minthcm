<?php

class SpendTimeNotifier
{
    protected $db;
    protected $sql;
    protected $spent_times_ids;
    protected $subject;
    protected $body_header;

    public function __construct($sql, $type)
    {
        $mod_strings = return_module_language('pl-PL', 'Schedulers');

        $this->sql = $sql;
        switch ($type) {
            case "times_without_schedules":
                $this->subject     = $mod_strings['LBL_SPENT_TIMES_WITHOUT_WORK_SCHEDULE_NOTIFICATION_SUBJECT'];
                $this->body_header = $mod_strings['LBL_SPENT_TIMES_WITHOUT_WORK_SCHEDULE_NOTIFICATION_BODY'];
                break;
            case "invalid_times":
                $this->subject     = $mod_strings['LBL_INVALID_SPENT_TIMES_NOTIFICATION_SUBJECT'];
                $this->body_header = $mod_strings['LBL_INVALID_SPENT_TIMES_NOTIFICATION_BODY'];
                break;
            case "times_work_schedule_other_users":
                $this->subject     = $mod_strings['LBL_FIND_SPENT_TIMES_ASSIGN_TO_DIFFERENT_USER_WORK_SCHEDULE_SUBJECT'];
                $this->body_header = $mod_strings['LBL_FIND_SPENT_TIMES_ASSIGN_TO_DIFFERENT_USER_WORK_SCHEDULE_BODY'];
                break;
            default:
                $this->subject     = $mod_strings['LBL_NOTIFIER_DEFAULT_SUBJECT'];
                $this->body_header = $mod_strings['LBL_NOTIFIER_DEFAULT_BODY'];
                break;
        }
        $this->db = DBManagerFactory::getInstance();
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setBodyHeader($body_header)
    {
        $this->body_header = $body_header;
    }

    public function notifyIfInvalidTimeFound()
    {
        $this->findInvalidSpendTimes();

        if (count($this->spent_times_ids) > 0) {
            $body = $this->buildBody();
            $this->sendReport($body);
        }
    }

    protected function findInvalidSpendTimes()
    {
        $result                = $this->db->query($this->sql);
        $this->spent_times_ids = array();

        while ($row = $this->db->fetchByAssoc($result)) {
            $this->spent_times_ids[] = $row['id'];
        }
    }

    protected function sendReport($body)
    {
        require_once("include/SugarPHPMailer.php");
        include("custom/config/admin_emails.php");

        $mail = new SugarPHPMailer();
        if (!empty($admin_emails)) {
            foreach ($admin_emails as $email) {
                $mail->AddAddress($email);
            }

            $mail->setMailerForSystem();
            $mail->Body        = $body;
            $mail->Subject     = $this->subject;
            $mail->ContentType = 'text/html';
            $mail->prepForOutbound();
            if (!$mail->Send()) {
                $GLOBALS['log']->fatal("Email Reminder: error sending e-mail (method: {$mail->Mailer}), (error: {$mail->ErrorInfo})");
            }
        }
    }

    protected function buildBody()
    {
        $spent_time_links = "";

        foreach ($this->spent_times_ids as $id) {
            $spent_time_links .= "<li>".$this->getURLBasedOnId($id)."</li>";
        }

        $body = "<div>".$this->body_header."Lista czasów: <br /><ul>".$spent_time_links."</ul></div>";

        return $body;
    }

    protected function getURLBasedOnId($id)
    {
        global $sugar_config;

        $url = $sugar_config['site_url']."?module=SpentTime&action=DetailView&record=".$id;
        return "<a href='".$url."' target='_blank'>".$url."</a>";
    }
}