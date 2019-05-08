<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.popup.php');

class WorkSchedulesViewPopup extends ViewPopup {

   public function display() {
      parent::display();
      if ( !empty($_REQUEST['workschedules_type_advanced']) ) {
         echo '<script>
            $( document ).ready(function() {
               $(\'#search_form_clear\').attr(\'onclick\',"SUGAR.searchForm.clear_form(this.form,[\"workschedules_type_advanced\"]); return false;"); //dopisujemy, aby funkcja czyszcząca ignorowała nasz filtr.
            });
            </script>';
      }
   }

}
