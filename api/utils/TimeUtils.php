<?php
namespace MintHCM\Utils;

use DateTime;
use DateTimeInterface;
use MintHCM\Data\TimeDate;

class TimeUtils
{
    public $timedate;

    public function __construct()
    {
        global $current_user;
        $this->timedate = new TimeDate($current_user);
    }

    public function getDateTimeObject($date_string, $reset_time = false)
    {
        $date_object = null;
        if (is_a($date_string, 'DateTime')) {
            $date_object = $date_string;
        }
        if (!$date_object) {
            $date_object = DateTime::createFromFormat(DateTimeInterface::ATOM, $date_string);
        }
        if (!$date_object) {
            $date_object = DateTime::createFromFormat($this->timedate->get_date_format(),
                $date_string);
        }
        if (!$date_object) {
            $date_object = DateTime::createFromFormat($this->timedate->get_db_date_format(),
                $date_string);
        }
        if (!$date_object) {
            $date_object = DateTime::createFromFormat($this->timedate->get_date_time_format(),
                $date_string);
        }
        if (!$date_object) {
            $date_object = DateTime::createFromFormat($this->timedate->get_db_date_time_format(),
                $date_string);
        }
        if (!empty($date_object) && true === $reset_time) {
            $date_object->setTime(0, 0);
        }
        return $date_object;
    }
}
