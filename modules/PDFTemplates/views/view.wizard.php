<?php

require_once 'include/MVC/View/views/view.edit.php';

class PDFTemplatesViewWizard extends ViewEdit {

   var $ev;
   var $type = 'wizard';
   var $useForSubpanel = false;  //boolean variable to determine whether view can be used for subpanel creates
   var $useModuleQuickCreateTemplate = false; //boolean variable to determine whether or not SubpanelQuickCreate has a separate display function
   var $showTitle = true;

   function PDFTemplatesViewWizard() {
      parent::getEditView();
   }

   function preDisplay() {
      parent::preDisplay();
      $metadataFile = $this->getMetaDataFile();
      $this->ev = new EditView();
      $this->ev->view = "WizardView";
      $this->ev->ss = & $this->ss;
      $this->ev->setup($this->module, $this->bean, $metadataFile);
   }

   function display() {
      $this->ev->process();
      echo $this->ev->display($this->showTitle, false);
   }

   function getMetaDataFile() {
      $metadataFile = null;
      $foundViewDefs = false;
      if ( file_exists('custom/modules/' . $this->module . '/metadata/wizarddefs.php') ) {
         $metadataFile = 'custom/modules/' . $this->module . '/metadata/wizarddefs.php';
         $foundViewDefs = true;
      } else {
         if ( file_exists('custom/modules/' . $this->module . '/metadata/metafiles.php') ) {
            require_once('custom/modules/' . $this->module . '/metadata/metafiles.php');
            if ( !empty($metafiles[$this->module]['wizarddefs.php']) ) {
               $metadataFile = $metafiles[$this->module]['wizarddefs.php'];
               $foundViewDefs = true;
            }
         } elseif ( file_exists('modules/' . $this->module . '/metadata/metafiles.php') ) {
            require_once('modules/' . $this->module . '/metadata/metafiles.php');
            if ( !empty($metafiles[$this->module]['wizarddefs.php']) ) {
               $metadataFile = $metafiles[$this->module]['wizarddefs.php'];
               $foundViewDefs = true;
            }
         }
      }
      $GLOBALS['log']->debug("metadatafile=" . $metadataFile);
      if ( !$foundViewDefs && file_exists('modules/' . $this->module . '/metadata/wizarddefs.php') ) {
         $metadataFile = 'modules/' . $this->module . '/metadata/wizarddefs.php';
      }
      return $metadataFile;
   }

}
