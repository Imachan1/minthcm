<?php
namespace Api\V8\Service;

use Api\V8\BeanDecorator\BeanManager;
use Api\V8\JsonApi\Helper\AttributeObjectHelper;
use Api\V8\JsonApi\Helper\RelationshipObjectHelper;
use Api\V8\JsonApi\Response\DataResponse;
use Api\V8\Param\CalendarDataParams;
use Slim\Http\Request;

class CalendarDataService
{
    protected $beanManager;
    protected $attributeHelper;
    protected $relationshipHelper;

    public function __construct(
        BeanManager $beanManager,
        AttributeObjectHelper $attributeHelper,
        RelationshipObjectHelper $relationshipHelper
    ) {
        $this->beanManager = $beanManager;
        $this->attributeHelper = $attributeHelper;
        $this->relationshipHelper = $relationshipHelper;
    }

    public function getCalendarData(CalendarDataParams $params, Request $request)
    {
        global $db, $timedate;
        $employee_id = $params->getId();
        $date = explode('-', $params->getDate());
        $year = $date[0];
        $month = $date[1];
        $modules = [
            'Meetings' => 'meeting',
            'Calls' => 'call',
            'Tasks' => 'task'       
        ];

        $data = [];

        $start_time = strtotime("01-" . $month . "-" . $year);
        $end_time = strtotime("+1 month", $start_time);
        $date_format = $timedate->get_db_date_format();

        $start_date = date($date_format, $start_time);
        $end_date = date($date_format, $end_time);

        $workschedule_ids = $db->query($this->getWorkscheduleIds($employee_id, $start_date, $end_date));
        while (($row = $db->fetchByAssoc($workschedule_ids)) != null) {
            $workschedule_bean = $this->beanManager->getBeanSafe('WorkSchedules', $row["workschedule_id"]);
            $data[] = [
                "workSchedule" => $this->getDataResponse(
                    $workschedule_bean,
                    null,
                    $request->getUri()->getPath() . '/' . $workschedule_bean->id
                ),
            ];
        }

        foreach ($modules as $key => $value) {
            $ids = $db->query($this->getModuleIds($employee_id, $start_date, $end_date, $key));
            while (($row = $db->fetchByAssoc($ids)) != null) {
                $bean = $this->beanManager->getBeanSafe($key, $row["id"]);
                $data[] = [
                    $value => $this->getDataResponse(
                        $bean,
                        null,
                        $request->getUri()->getPath() . '/' . $bean->id
                    ),
                ];
            }
        }

        return $data;
    }

    protected function getWorkscheduleIds($employee_id, $start_date, $end_date) 
    {
        return "SELECT id as workschedule_id FROM workschedules
                WHERE assigned_user_id = '{$employee_id}'
                    AND schedule_date >= '{$start_date}'
                    AND schedule_date < '{$end_date}'
                    AND deleted = 0
                ORDER BY schedule_date ASC";
    }

    protected function getModuleIds($employee_id, $start_date, $end_date, $module) 
    {
        $table = strtolower($module);
        return "SELECT id FROM {$table}
                WHERE assigned_user_id = '{$employee_id}' 
                    AND date_start >= '{$start_date}' 
                    AND date_start < '{$end_date}' 
                    AND deleted = 0 
                ORDER BY date_start ASC";
    }

    public function getDataResponse(\SugarBean $bean, $fields = null, $path = null)
    {
        $dataResponse = new DataResponse($bean->getObjectName(), $bean->id);
        $dataResponse->setAttributes($this->attributeHelper->getAttributes($bean, $fields));
        return $dataResponse;
    }

}
