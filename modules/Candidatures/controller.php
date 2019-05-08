<?php

SugarAutoLoader::requireWithCustom('modules/Candidatures/CandidatureConverter.php');

class CandidaturesController extends SugarController {

   public function action_convertToEmployee() {
      $record_id = filter_input(INPUT_GET, 'record_id', FILTER_SANITIZE_SPECIAL_CHARS);
      $convert_candidature = new CandidatureConverter($record_id);
      echo '<id>' . $convert_candidature->convert() . '</id>';
   }

}
