<?php

class SecurityGroupsApi {
   
   public function checkParentGroup($params) {
        $sql = "SELECT parent_id FROM securitygroups WHERE id = '{$params['parent_id']}' AND deleted = 0";
        $db = DBManagerFactory::getInstance();
        $result = $db->getOne($sql);
        if ($params['id'] == $result && !is_null($params['id']))
        {
            return false;
        }
        else
        {
            return true;
        }
   }
   
}