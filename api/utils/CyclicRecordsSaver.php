<?php
namespace MintHCM\Utils;

use DateInterval;
use DateTime;
use MintHCM\Data\BeanFactory;
use MintHCM\Data\MintBean;
use MintHCM\Utils\LegacyConnector;
use MintHCM\Utils\TimeUtils;
use Doctrine\ORM\EntityManagerInterface;

class CyclicRecordsSaver
{
    protected $timeUtils;

    protected const FIELDS_TO_SKIP = [
        'id',
        'date_entered',
        'date_modified',
        'date_indexed',
    ];

    protected const RELATIONSHIP_LINKS_TO_COPY = [
        'Meetings' => [
            'users',
            'candidates',
            'resources',
        ],
        'Calls' => [
            'users',
            'candidates',
            'resources',
        ],
    ];

    public function __construct(protected $bean, protected EntityManagerInterface $entityManager)
    {
        $this->timeUtils = new TimeUtils();
    }

    public function run(): void
    {
        if (
            empty($this->bean->id)
            || empty($this->bean->repeat_type)
            || $this->hasCyclicRecords()
        ) {
            return;
        }
        $this->saveCyclicRecords();
    }

    public function hasCyclicRecords(): bool
    {
        $entity_class = $this->getEntityClassName();
        $queryBuilder = $this->entityManager->createQueryBuilder($entity_class);
        $children = $queryBuilder->select('e.id')
            ->from($entity_class, 'e')
            ->where('e.repeat_parent_id = :parentId')
            ->setParameter('parentId', $this->bean->id)
            ->getQuery()
            ->getArrayResult();
            
        return !empty($children);
    }

    protected function getEntityClassName(): string
    {
        $moduleName = $this->bean->module_name; 
        return "MintHCM\\Api\\Entities\\{$moduleName}";
    }

    protected function saveCyclicRecords()
    {
        $start_dates = $this->calculateStartDates();

        $start_date_object = $this->timeUtils->getDateTimeObject($this->bean->date_start);
        $end_date_object = $this->timeUtils->getDateTimeObject($this->bean->date_end);

        $duration = $start_date_object->diff($end_date_object);

        foreach ($start_dates as $start_date) {
            $new_bean = BeanFactory::newBean($this->bean->module_name);
            foreach ($this->bean->field_defs as $key => $value) {
                if (in_array($key, self::FIELDS_TO_SKIP) || in_array($value['type'], ['link'])) {
                    continue;
                }
                $new_bean->$key = $this->bean->$key;
            }
            $new_bean->date_start = $start_date;
            $new_bean->date_end = $this->calculateEndDate($start_date, $duration);
            $new_bean->repeat_parent_id = $this->bean->id;
            $new_bean->save();
            $this->copyRelationshipFields($new_bean);
        }
        return;
    }

    protected function calculateStartDates(): array
    {
        $date_interval = new DateInterval("P{$this->bean->repeat_interval}D");
        switch ($this->bean->repeat_type) {
            case 'Weekly':
                $interval = (int) $this->bean->repeat_interval * 7;
                $date_interval = new DateInterval("P{$interval}D");
                break;
            case 'Monthly':
                $date_interval = new DateInterval("P{$this->bean->repeat_interval}M");
                break;
            case 'Yearly':
                $date_interval = new DateInterval("P{$this->bean->repeat_interval}Y");
                break;
            default:
                break;
        }

        $days_of_week = [];
        if (!empty($this->bean->repeat_dow)) {
            $days_of_week = str_split($this->bean->repeat_dow);
        }

        $start_dates = $this->walkDates(
            $this->bean->date_start,
            $date_interval,
            $this->bean->repeat_count,
            $this->bean->repeat_until,
            $days_of_week
        );

        return $start_dates;
    }

    protected function walkDates(string $start_date, DateInterval $interval, ?int $count = null, ?string $until = null, array $days_of_week = []): array
    {

        if (empty($start_date) || empty($interval) || (empty($count) && empty($until))) {
            return [];
        }

        $dates = [];
        $current_date = $this->timeUtils->getDateTimeObject($start_date);
        $days_of_week = [];

        if (!empty($count) && $count > 0) {
            $this->getDatesByCount($dates, $current_date, $interval, $count, $days_of_week);
        } else if (!empty($until)) {
            $this->getDatesByUntil($dates, $current_date, $interval, $until, $days_of_week);
        }

        return $dates;
    }

    protected function getDatesByUntil(array &$dates, DateTime $current_date, DateInterval $interval, string $until, array $days_of_week): void
    {
        $until_date = $this->timeUtils->getDateTimeObject($until . ' 00:00:00');
        if (!empty($days_of_week)) {
            $this->addOnGivenDays($dates, $current_date, $days_of_week, null, $until_date);
        }

        $current_date->add($interval);
    
        while ($current_date <= $until_date) {
            if (!empty($days_of_week)) {
                $this->addOnGivenDays($dates, $current_date, $days_of_week, null, $until_date);
                $current_date->add($interval);
                continue;
            }

            $dates[] = $this->timeUtils->timedate->asDb($current_date);
            $current_date->add($interval);
        }
    }
    protected function getDatesByCount(array &$dates, DateTime $current_date, DateInterval $interval, int $count, array $days_of_week): void
    {
        if (!empty($days_of_week)) {
            $this->addOnGivenDays($dates, $current_date, $days_of_week, $count);
        }
        
        while (count($dates) < $count) {
            $current_date->add($interval);
            if (!empty($days_of_week)) {
                $this->addOnGivenDays($dates, $current_date, $days_of_week, $count);
                continue;
            }

            $dates[] = $this->timeUtils->timedate->asDb($current_date);
        }
    }

    protected function addOnGivenDays(array &$dates, DateTime $current_date, array $days_of_week, ?int $count = null, ?DateTime $until = null): void
    {
        if (!empty($count)) {
            foreach ($days_of_week as $day) {
                if (count($dates) >= $count) {
                    break;
                }
                $date = $this->calculateDateByDay($current_date, $day);
                $dates[] = $this->timeUtils->timedate->asDb($date);
            }
        } else if (!empty($until)) {
            foreach ($days_of_week as $day) {
                $date = $this->calculateDateByDay($current_date, $day);
                if ($date > $until) {
                    continue;
                }

                $dates[] = $this->timeUtils->timedate->asDb($date);
            }
        }
    }

    protected function calculateDateByDay(DateTime $current_date, string $day): DateTime
    {
        global $app_list_strings;
        $day_name = strtolower($app_list_strings['dom_cal_day_long'][$day]);
        $date = clone $current_date;
        $current_day_name = strtolower($date->format('l'));
        if ($current_day_name === $day_name && $this->timeUtils->timedate->asDb($date) !== $this->bean->date_start) {
            return $date;
        }

        $original_time = $date->format('H:i:s');
        $date->modify("next $day_name");
        $date->setTime(...explode(':', $original_time));

        return $date;
    }

    protected function calculateEndDate(string $start_date, DateInterval $duration): string
    {
        $start_date_object = $this->timeUtils->getDateTimeObject($start_date);
        $end_date_object = clone $start_date_object;
        $end_date_object->add($duration);
        return $this->timeUtils->timedate->asDb($end_date_object);
    }

    protected function copyRelationshipFields(MintBean $new_bean): void
    {
        $module = $this->bean->module_dir;
        if (!array_key_exists($module, self::RELATIONSHIP_LINKS_TO_COPY)) {
            return;
        }

        $links_to_copy = self::RELATIONSHIP_LINKS_TO_COPY[$module];
        foreach ($links_to_copy as $field) {
            $related_beans = $this->bean->get_linked_beans($field);
            if ($new_bean->load_relationship($field)) {
                foreach ($related_beans as $related_bean) {
                    $new_bean->$field->add($related_bean->id);
                }
            }
        }
    }
}
