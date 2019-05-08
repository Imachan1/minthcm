<?php

require_once('include/MVC/View/SugarView.php');
require_once('include/EditView/EditView2.php');

/**
 * The class create view which we can change default email template used to send raports.
 */
class ScheduleReportsViewEmailOptions extends SugarView {

   var $ev;
   var $type = 'emailoptions';
   var $useForSubpanel = false;  //boolean variable to determine whether view can be used for subpanel creates
   var $useModuleQuickCreateTemplate = false; //boolean variable to determine whether or not SubpanelQuickCreate has a separate display function
   var $showTitle = true;
   var $path = 'custom/config/pdf_email_cfg.php';

   function ViewEmailOptions() {
      parent::SugarView();
   }

   function preDisplay() {
      parent::preDisplay();
      $metadataFile = $this->getMetaDataFile();
      $this->ev = new EditView();
      $this->ev->view = "emailoptionsView";
      $this->ev->ss = & $this->ss; //new Sugar_Smarty();
      $this->ev->setup($this->module, $this->bean, $metadataFile);
   }

   function display() {
      if ( $_REQUEST['action'] == 'saveOptions' ) {
         header("index.php?module=ScheduleReports&action=index");
         return;
      }
      if ( is_file($this->path) ) {
         require_once $this->path;
      }
      $this->bean->email_template_id = $options['email_template_id']['ScheduleReports'];
      $form_name = "OptionsView";
      $this->ev->formName = $form_name;
      $email_templates_arr = get_bean_select_array(true, 'EmailTemplate', 'name', '', 'name', true);
      $TMPL_DRPDWN_GENERATE = get_select_options_with_id($email_templates_arr, $this->bean->email_template_id);
      $PROFORMA_TMPL_DRPDWN_GENERATE = get_select_options_with_id($email_templates_arr, $this->bean->proforma_email_template_id);
      $this->ev->ss->assign('MOD', $mod_strings);
      $this->ev->ss->assign('APP', $app_strings);
      $this->ev->ss->assign("TMPL_DRPDWN_GENERATE", $TMPL_DRPDWN_GENERATE);
      $this->ev->ss->assign("PROFORMA_TMPL_DRPDWN_GENERATE", $PROFORMA_TMPL_DRPDWN_GENERATE);

      $this->ev->ss->assign("PDF_DIR", $this->bean->pdf_dir);
      $this->ev->process(true, $form_name);

      echo $this->ev->display();
   }

   protected function _getModuleTitleParams() {
      global $mod_strings;

      return array(
         "<a href='index.php?module=ScheduleReports&action=index'>" . translate('LBL_MODULE_NAME', 'ScheduleReports') . "</a>",
         $mod_strings['LBL_OPTIONS_TITLE']
      );
   }

}
