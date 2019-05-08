<?php

SugarAutoLoader::requireWithCustom('modules/EmployeeRoles/PositionCreator.php');

class EmployeeRolesController extends SugarController {

   function action_createPosition() {
      $record_id = filter_input(INPUT_GET, 'record_id', FILTER_SANITIZE_SPECIAL_CHARS);
      $position_creator = new PositionCreator($record_id);
      echo '<id>' . $position_creator->create() . '</id>';
   }

}
