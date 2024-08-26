<?php

require_once 'include/Notifications/Notification.php';

class UpdateNewsByProspectLists
{
    public function run()
    {
        $prospect_list_ids = $this->getProspectListIDs();
        foreach ($prospect_list_ids as $prospect_list_id) {
            $prospect_list_employee_ids = $this->getProspectListEmployeeIDs($prospect_list_id);
            $prospect_list = BeanFactory::getBean('ProspectLists', $prospect_list_id);
            $report_employee_ids = $this->getReportEmployeeIDs($prospect_list);
            if (!empty($report_employee_ids) || !empty($prospect_list_employee_ids)) {
                $this->createOrDeleteUsersNewsForEmployee($prospect_list_employee_ids, $prospect_list, $report_employee_ids);
            }
        }
    }

    protected function getProspectListIDs()
    {
        global $db;
        $prospect_list_ids = [];
        $sql = "
            SELECT
                id
            FROM 
                prospect_lists
            WHERE
                deleted='0'
            AND
                automatic_update='1'
        ";
        $prospect_lists = $db->query($sql);
        while (($row = $db->fetchByAssoc($prospect_lists)) != null) {
            $prospect_list_ids[] = $row['id'];
        }
        return $prospect_list_ids;
    }

    protected function getProspectListEmployeeIDs($prospect_list_id)
    {
        global $db;
        $prospect_list_employee_ids = [];
        $sql = "
        SELECT
            related_id
        FROM 
            prospect_lists_prospects
        WHERE
            deleted='0'
        AND
            prospect_list_id = {$db->quoted($prospect_list_id)}
        AND
            related_type = 'Employees'
        ";
        $prospect_list_employees = $db->query($sql);
        while (($row = $db->fetchByAssoc($prospect_list_employees)) != null) {
            $prospect_list_employee_ids[] = $row['related_id'];
        }
        return $prospect_list_employee_ids;
    }

    protected function getReportEmployeeIDs($prospect_list)
    {
        $assigned_advanced_report = BeanFactory::getBean('KReports', $prospect_list->kreport_id);
        $reportParams = array('toCSV' => true);
        $report_results = $assigned_advanced_report->getSelectionResults($reportParams);
        $report_employee_ids = [];
        foreach ($report_results as $report_result) {
            if ($report_result['sugarRecordModule'] == 'Employees') {
                $report_employee_ids[] = $report_result['sugarRecordId'];
            }
        }
        return $report_employee_ids;
    }

    protected function createOrDeleteUsersNewsForEmployee($prospect_list_employee_ids, $prospect_list, $report_employee_ids)
    {
        $employees_to_add = array_diff($report_employee_ids, $prospect_list_employee_ids);
        $employees_to_remove = array_diff($prospect_list_employee_ids, $report_employee_ids);
        $prospect_list->load_relationship('news');
        $prospect_list_news_list = $prospect_list->news->get();
        $this->handleEmployeesToAdd($employees_to_add, $prospect_list->id, $prospect_list_news_list);
        $this->handleEmployeesToRemove($employees_to_remove, $prospect_list, $prospect_list_news_list);
    }

    protected function handleEmployeesToAdd($employees_to_add, $prospect_list_id, $prospect_list_news_list)
    {
        foreach ($employees_to_add as $employee_to_add) {
            $this->createNewProspectListProspectsTableRecord($employee_to_add, $prospect_list_id);
            foreach ($prospect_list_news_list as $prospect_list_news) {
                $this->createUsersNewsRecord($prospect_list_news, $employee_to_add);
                $this->addUserPrivateGroupToNews($prospect_list_news, $employee_to_add);
            }
        }
    }

    protected function createNewProspectListProspectsTableRecord($employee_to_add, $prospect_list_id)
    {
        global $db;
        $new_id = create_guid();
        $insert_sql = "
                    INSERT INTO
                        prospect_lists_prospects (id, prospect_list_id, related_id, related_type, deleted)
                    VALUES
                        ('{$new_id}', '{$prospect_list_id}', '{$employee_to_add}', 'Employees', '0') 
                ";
        $db->query($insert_sql);
    }

    protected function createUsersNewsRecord($prospect_list_news, $employee_to_add)
    {
        $news = BeanFactory::getBean('News', $prospect_list_news);
        $users_news = BeanFactory::newBean('UsersNews');
        $users_news->news_id = $news->id;
        $users_news->news_name = $news->name;
        $users_news->assigned_user_id = $employee_to_add;
        $users_news->save();
        $override = [
            'description' => translate("LBL_NEW_USERS_NEWS", "News") . ": " . $news->name,
            'url_redirect' => 'index.php?module=Home&action=index',
        ];
        (new Notification())->setRelatedBeanFromBean($news)->setAssignedUserId($employee_to_add)->setName($users_news->news_name)->setType('UserNews')
            ->simpleAlert(true, $override)->WebPush(false, true, $override);
    }

    protected function addUserPrivateGroupToNews($prospect_list_news, $employee_to_add)
    {
        $news = BeanFactory::getBean('News', $prospect_list_news);
        $user = BeanFactory::getBean('Users', $employee_to_add);
        $user_private_group_id = $user->getUserPrivateGroup();
        $news->load_relationship('SecurityGroups');
        $news->SecurityGroups->add($user_private_group_id);
    }

    protected function handleEmployeesToRemove($employees_to_remove, $prospect_list, $prospect_list_news_list)
    {
        foreach ($employees_to_remove as $employee_to_remove) {
            $this->deleteProspectListsProspectsTableRecord($employee_to_remove, $prospect_list);
            foreach ($prospect_list_news_list as $prospect_list_news) {
                $this->deleteUsersNewsRecord($employee_to_remove, $prospect_list_news);
                $this->deleteUserPrivateGroupFromNews($employee_to_remove, $prospect_list_news);
            }
        }
    }

    protected function deleteProspectListsProspectsTableRecord($employee_to_remove, $prospect_list)
    {
        global $db;
        $delete_sql = "
                    DELETE FROM
                        prospect_lists_prospects 
                    WHERE
                       related_id = '{$employee_to_remove}'
                    AND
                       prospect_list_id = '{$prospect_list->id}'
                ";
        $db->query($delete_sql);
    }

    protected function deleteUsersNewsRecord($employee_to_remove, $prospect_list_news)
    {
        $news = BeanFactory::getBean('News', $prospect_list_news);
        $news->load_relationship('usersnews');
        foreach ($news->usersnews->get() as $users_news_id) {
            $users_news = BeanFactory::getBean('UsersNews', $users_news_id);
            if ($users_news->assigned_user_id == $employee_to_remove) {
                $users_news->mark_deleted($users_news->id);
            }
        }
    }

    protected function deleteUserPrivateGroupFromNews($employee_to_remove, $prospect_list_news)
    {
        $news = BeanFactory::getBean('News', $prospect_list_news);
        $user = BeanFactory::getBean('Users', $employee_to_remove);
        $user_private_group_id = $user->getUserPrivateGroup();
        $news->load_relationship('SecurityGroups');
        $news->SecurityGroups->delete($user_private_group_id);
    }
}
