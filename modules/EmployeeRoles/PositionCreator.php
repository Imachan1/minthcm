<?php

class PositionCreator {

   const POSITIONS_MODULE_NAME = "Positions";
   const ROLES_MODULE_NAME = "EmployeeRoles";

   protected $record_id;

   public function __construct($record_id) {
      $this->record_id = $record_id;
   }

   public function create() {
      $role = BeanFactory::getBean(self::ROLES_MODULE_NAME);
      if ( $role->retrieve($this->record_id) ) {
         $position = BeanFactory::newBean(self::POSITIONS_MODULE_NAME);
         $position->name = $role->name;
         $position->status = $role->status;
         $position->assigned_user_id = $role->assigned_user_id;
         $position->save();
         $this->copy('benefits', $position);
         $this->copy('responsibilities', $position);
         $this->copyCompetencyRatings($position);
         return $position->id;
      }
   }

   protected function copy($module_name, $position) {
      global $db;
      $table_name = $module_name . "_employeeroles";
      $relationship_name = $module_name . "_positions";
      $id_field_name = array(
         'benefits' => 'benefit_id',
         'responsibilities' => 'responsibility_id',
      );
      $sql = "SELECT {$id_field_name[$module_name]} FROM {$table_name} WHERE role_id = '{$this->record_id}' AND deleted = 0";
      $result = $db->query($sql);
      $beans_ids = array();
      while ( $row = $db->fetchByAssoc($result) ) {
         $beans_ids[] = $row[$id_field_name[$module_name]];
      }
      if ( $position->load_relationship($relationship_name) ) {
         $position->$relationship_name->add($beans_ids);
      }
   }

   protected function copyCompetencyRatings($position) {
      global $db;
      $sql = "SELECT id FROM competencyratings WHERE parent_type = '" . self::ROLES_MODULE_NAME . "' AND parent_id = '{$this->record_id}' AND deleted = 0";
      $result = $db->query($sql);
      while ( $row = $db->fetchByAssoc($result) ) {
         $competency_rating = BeanFactory::getBean('CompetencyRatings');
         if ( $competency_rating->retrieve($row['id']) ) {
            $new_competency_rating = BeanFactory::newBean('CompetencyRatings');
            $new_competency_rating->rating = $competency_rating->rating;
            $new_competency_rating->competency_id = $competency_rating->competency_id;
            $new_competency_rating->competency_name = $competency_rating->competency_name;
            $new_competency_rating->assigned_user_id = $competency_rating->assigned_user_id;
            $new_competency_rating->parent_type = self::POSITIONS_MODULE_NAME;
            $new_competency_rating->parent_name = $position->name;
            $new_competency_rating->parent_id = $position->id;
            $new_competency_rating->save();
         }
      }
   }

}
