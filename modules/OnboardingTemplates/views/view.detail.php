<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.detail.php');

class OnboardingTemplatesViewDetail extends ViewDetail {

   public function display() {
      $result = parent::display();
      echo '<script type="text/javascript" src="include/SugarFields/Fields/Datetimecombo/Datetimecombo.js"></script>';
      echo '<script id="generate-onboarding-offboarding-template" type="text/template">';
      echo file_get_contents('modules/OnboardingTemplates/tpl/generateOnboardingOffboarding.tpl');
      echo '</script>';
      return $result;
   }

}
