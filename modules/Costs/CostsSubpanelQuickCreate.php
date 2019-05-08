<?php

require_once('include/EditView/SubpanelQuickCreate.php');

class CostsSubpanelQuickCreate extends SubpanelQuickCreate {

   public function process($module) {
      $form_name = 'form_Subpanel' . $this->ev->view . '_' . $module;
      $this->ev->formName = $form_name;
      $this->ev->process(true, $form_name);
      if ( $_REQUEST['parent_type'] == "Delegations" ) {
         unset($this->ev->fieldDefs['type']['options']['transport']);
      } else if ( $_REQUEST['parent_type'] == "Transportations" ) {
         unset($this->ev->fieldDefs['type']['options']['restaurant']);
         unset($this->ev->fieldDefs['type']['options']['accommodation']);
         unset($this->ev->fieldDefs['type']['options']['other']);
      }
      echo $this->ev->display(false, true);
   }

}
