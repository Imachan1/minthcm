<?php

class SalaryRange extends Basic
{
    public $new_schema = true;
    public $module_dir = 'SalaryRange';
    public $object_name = 'SalaryRange';
    public $table_name = 'salaryrange';
    public $importable = true;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $tag;
    public $tag_link;
    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $activities;
    public $following;
    public $following_link;
    public $my_favorite;
    public $favorite_link;
    public $commentlog;
    public $commentlog_link;
    public $locked_fields;
    public $locked_fields_link;

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':return true;
        }
        return false;
    }

    public function save($check_notify = false)
    {
        $this->name = $this->position_name . ' - ' . $this->start_date . ' - ' . $this->end_date;
        parent::save();
    }

}
