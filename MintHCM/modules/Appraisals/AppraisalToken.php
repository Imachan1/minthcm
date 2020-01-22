<?php

class AppraisalToken
{
    public $id;
    public $date_entered;
    public $deleted;
    public $status;
    public $expired_date;
    public $token;
    public $employee_id;
    public $appraisal_id;

    public function save($employee_id, $appraisal_id, $evaluator_has_changed = false)
    {
        global $db;
        if($evaluator_has_changed){
            $select_sql = "SELECT id from appraisals_tokens WHERE employee_id = '{$employee_id}' AND appraisal_id = '{$appraisal_id}' AND deleted = 0";
            $id = $db->getOne($select_sql);
            if (!empty($id)) {
                $update_sql = "UPDATE appraisals_tokens SET status = 0 WHERE id = '{$id}'";
            }
            $id = create_guid();
            $token = create_guid();
            $insert_sql = "INSERT INTO appraisals_tokens (id, date_entered, deleted, status, expired_date, token, employee_id, appraisal_id) VALUES ('{$id}', NOW(), 0, 1, DATE_ADD(NOW(), INTERVAL +{$this->sugar_config['days_to_token_expiration']} DAY), '{$token}', '{$employee_id}', '{$appraisal_id}')";
            if ($db->query($insert_sql) === true) {
                return $id;
            }
        }
        return null;
    }

    public function retrieve($appraisal_token_id)
    {
        global $db;
        $select_sql = "SELECT * FROM appraisals_tokens WHERE id = '{$appraisal_token_id}'";
        $appraisal_token = $db->fetchOne($select_sql);
        if (!empty($appraisal_token)) {
            $this->id = $appraisal_token['id'];
            $this->date_entered = $appraisal_token['date_entered'];
            $this->deleted = $appraisal_token['deleted'];
            $this->status = $appraisal_token['status'];
            $this->expired_date = $appraisal_token['expired_date'];
            $this->token = $appraisal_token['token'];
            $this->employee_id = $appraisal_token['employee_id'];
            $this->appraisal_id = $appraisal_token['appraisal_id'];
        }
        return $this;

    }

    public function mark_deleted($appraisal_token_id)
    {
        global $db;
        if (!empty($appraisal_token_id)) {
            $update_sql = "UPDATE appraisals_tokens SET deleted = 1 WHERE id = '{$appraisal_token_id}'";
            $db->query($update_sql);
        }
    }

    public function deactivate($appraisal_token_id)
    {
        global $db;
        if (!empty($appraisal_token_id)) {
            $update_sql = "UPDATE appraisals_tokens SET status = 0 WHERE id = '{$appraisal_token_id}'";
            $db->query($update_sql);
        }
    }
}