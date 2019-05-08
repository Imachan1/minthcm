<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('modules/SecurityGroups/SecurityGroup.php');
require_once('modules/SecurityGroups/SecurityGroupUserRelationship.php');

class PrivateGroup extends SecurityGroup {

   protected $user;
   protected $security_group;
   protected $user_assigned_private_groups;

   const SECURITY_GROUPS_MODULE_NAME = "SecurityGroups";

   public function __construct($user = null) {
      $this->user = $user;
   }

   public function create() {
      $this->security_group = BeanFactory::newBean(self::SECURITY_GROUPS_MODULE_NAME);
      $this->security_group->name = $this->user->last_name . ' ' . $this->user->first_name . ' - Private Group';
      $this->security_group->group_type = 'private';
      $this->security_group->assigned_user_id = $this->user->id;
      $this->security_group->save();
      $this->addSupervisorToPrivateGroup($this->security_group->id, $this->user->id);
   }

   public function delete() {
      if ( $this->getUserPrivateGroup($this->user->id) ) {
         $this->security_group->mark_deleted($this->security_group->id);
      }
   }

   public function update($new_reports_to_id, $old_reports_to_id) {
      if ( $this->getUserPrivateGroup($this->user->id) ) {
         $this->getUserAssignedPrivateGroups($this->user->id);
         if ( !empty($old_reports_to_id) ) {
            $this->delSupervisorFromPrivateGroup($this->security_group->id, $old_reports_to_id);
            $this->delSupervisorFromUserAssignedPrivateGroups($old_reports_to_id);
         }
         if ( !empty($new_reports_to_id) ) {
            $this->addSupervisorToPrivateGroup($this->security_group->id, $new_reports_to_id);
            $this->addSupervisorToUserAssignedPrivateGroups($new_reports_to_id);
         }
      }
   }

   protected function addSupervisorToPrivateGroup($pg_id, $user_id) {
      $sgur = new SecurityGroupUserRelationship();
      $sgur->user_id = $user_id;
      $sgur->securitygroup_id = $pg_id;
      $sgur->save();
      $reports_to_id = User::getUserSupervisiorID($user_id);
      if ( $reports_to_id ) {
         $this->addSupervisorToPrivateGroup($pg_id, $reports_to_id);
      }
   }

   protected function delSupervisorFromPrivateGroup($pg_id, $user_id) {
      $sgur = new SecurityGroupUserRelationship();
      if ( $sgur->retrieve_by_string_fields(array( 'securitygroup_id' => $pg_id, 'user_id' => $user_id )) ) {
         $sgur->mark_deleted($sgur->id);
      }
      $reports_to_id = User::getUserSupervisiorID($user_id);
      if ( $reports_to_id ) {
         $this->delSupervisorFromPrivateGroup($pg_id, $reports_to_id);
      }
   }

   protected function getUserPrivateGroup($user_id) {
      global $db;
      $sql = "SELECT id FROM securitygroups WHERE group_type = 'private' AND assigned_user_id = '{$user_id}' AND deleted = 0";
      $result = $db->getOne($sql);
      if ( $result ) {
         $group = BeanFactory::getBean('SecurityGroups', $result);
         if ( $group && $group->id ) {
            $this->security_group = $group;
            return $group;
         }
      }
      return null;
   }

   protected function getUserAssignedPrivateGroups($id) {
      global $db;
      $this->user_assigned_private_groups = array();
      $sql = "SELECT sg.id FROM securitygroups_users sgu LEFT JOIN securitygroups sg ON (sg.id = sgu.securitygroup_id) ";
      $sql .= " WHERE sgu.user_id = '{$id}' ";
      $sql .= " AND sg.group_type='private' AND sg.assigned_user_id <> sgu.user_id AND sg.deleted = 0 AND sgu.deleted = 0 ";
      $result = $db->query($sql);
      while ( ($row = $db->fetchByAssoc($result)) != null ) {
         $this->user_assigned_private_groups[] = $row['id'];
      }
   }

   protected function delSupervisorFromUserAssignedPrivateGroups($user_id) {
      if ( $this->user_assigned_private_groups ) {
         foreach ( $this->user_assigned_private_groups as $pg_id ) {
            $this->delSupervisorFromPrivateGroup($pg_id, $user_id);
         }
      }
   }

   protected function addSupervisorToUserAssignedPrivateGroups($user_id) {
      if ( $this->user_assigned_private_groups ) {
         foreach ( $this->user_assigned_private_groups as $pg_id ) {
            $this->addSupervisorToPrivateGroup($pg_id, $user_id);
         }
      }
   }

}
