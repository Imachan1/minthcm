<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/SugarObjects/forms/FormBase.php');

class WorkSchedulesFormBase extends FormBase {

   protected static function updateFields(SugarBean $bean, $fields = array(), $prefix = '') {
      foreach ( $fields as $field ) {
         if ( isset($_REQUEST[$prefix . $field]) ) {
            $bean->$field = $_REQUEST[$prefix . $field];
         }
      }
   }

   public function handleSave($prefix = '', $redirect = true, $useRequired = false) {

      $curModule = isset($_REQUEST[$prefix . 'current_module']) ?
              $_REQUEST[$prefix . 'current_module'] : '';
      $id = isset($_REQUEST[$prefix . 'record']) ?
              $_REQUEST[$prefix . 'record'] : '';

      if ( $curModule == 'WorkSchedules' ) {
         $bean = !empty($id) ? BeanFactory::getBean('WorkSchedules', $id) : null;

         if ( $bean && $bean->id === $id ) {
            static::updateFields($bean, array(
               'name',
               'status',
               'date_start',
               'date_end',
               'duration_hours',
               'duration_minutes',
               'description',
               'type',
               'assigned_user_id',
               'repeat_type',
               'repeat_interval',
               'repeat_dow',
               'repeat_until',
               'repeat_count',
               'repeat_parent_id',
               'delegation_duration',
               'occasional_leave_type',
               'supervisor_acceptance',
               'comments',
            ));
            $bean->save();
         }
      }
      return $bean;
   }

}
