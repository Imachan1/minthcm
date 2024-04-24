<?php

class CandidatesRepository
{
    public static function getDuplicatedCandidatesEmployeesRecordsIds($bean)
    {
        $db = DBManagerFactory::getInstance();
        $result_employees = $db->query(static::getDuplicateQueryEmployees($bean));
        $result_candidates = $db->query(static::getDuplicateQueryCandidates($bean));
        $result_candidate_employee = $db->query(static::getCandidateEmployee($bean));
        $rows = [];
        $candidate_employee_id = '';
        while (($row = $db->fetchByAssoc($result_candidate_employee)) != null) {
            if (!isset($rows[$row['id']]) && $bean->id != $rows[$row['id']]) {
                $candidate_employee_id = $row['id'];
            }
        }
        while (($row = $db->fetchByAssoc($result_employees)) != null) {
            if (!isset($rows[$row['id']]) && $bean->id != $rows[$row['id']] && $row['id'] !== $candidate_employee_id) {
                $rows[] = $row['id'];
            }
        }
        while (($row = $db->fetchByAssoc($result_candidates)) != null) {
            if (!isset($rows[$row['id']]) && $bean->id != $rows[$row['id']]) {
                $rows[] = $row['id'];
            }
        }
        return $rows;
    }

    protected static function getDuplicateQueryEmployees($bean)
    {
        $duplicated_employees = "SELECT 
                                    users.id
                                FROM 
                                    email_addr_bean_rel as er
                                JOIN 
                                    email_addresses as ea ON er.email_address_id = ea.id
                                JOIN 
                                    users ON users.id = er.bean_id
                                WHERE 
                                    ea.email_address = '{$bean->email1}'
                                    AND users.phone_mobile = '{$bean->phone_mobile}'
                                    AND users.show_on_employees = 1
        ";
        return $duplicated_employees;
    }

    protected static function getCandidateEmployee($bean)
    {
        $candidate_employee_query = "SELECT 
                                    users.id
                                FROM 
                                    email_addr_bean_rel as er
                                JOIN 
                                    email_addresses as ea ON er.email_address_id = ea.id
                                JOIN 
                                    users ON users.id = er.bean_id
                                JOIN 
                                    candidates_employees ON candidates_employees.employee_id = users.id
                                WHERE 
                                    ea.email_address = '{$bean->email1}'
                                    AND users.phone_mobile = '{$bean->phone_mobile}'
                                    AND users.show_on_employees = 1
                                    AND candidates_employees.candidate_id = '{$bean->id}'
                            ";
        return $candidate_employee_query;
    }

    protected static function getDuplicateQueryCandidates($bean)
    {
        $duplicated_candidates = "SELECT 
                                    candidates.id
                                FROM 
                                    email_addr_bean_rel as er
                                JOIN 
                                    email_addresses as ea ON er.email_address_id = ea.id
                                JOIN 
                                    candidates ON candidates.id = er.bean_id
                                WHERE 
                                    ea.email_address = '{$bean->email1}'
                                    AND candidates.phone_mobile = '{$bean->phone_mobile}'
                                    AND candidates.id != '{$bean->id}'
        ";
        return $duplicated_candidates;
    }
}
