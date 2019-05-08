<?php

require_once 'include/MVC/View/views/view.edit.php';

class KTemplatesViewWizard extends ViewEdit {

   var $ev;
   var $type = 'wizard';
   var $useForSubpanel = false;  //boolean variable to determine whether view can be used for subpanel creates
   var $useModuleQuickCreateTemplate = false; //boolean variable to determine whether or not SubpanelQuickCreate has a separate display function
   var $showTitle = true;

   public function __construct() {
      parent::__construct();
   }

   function preDisplay() {
      parent::preDisplay();
      $metadataFile = $this->getMetaDataFile($this->module);
      $this->ev = new EditView();
      $this->ev->view = "WizardView";
      $this->ev->ss = & $this->ss;
      $this->ev->setup($this->module, $this->bean, $metadataFile);
   }

   public function getMetaDataFile($module) {
      $metadataFile = null;
      $foundViewDefs = false;
      if ( file_exists('custom/modules/' . $module . '/metadata/wizarddefs.php') ) {
         $metadataFile = 'custom/modules/' . $module . '/metadata/wizarddefs.php';
         $foundViewDefs = true;
      } else {
         if ( file_exists('custom/modules/' . $module . '/metadata/metafiles.php') ) {
            require_once('custom/modules/' . $module . '/metadata/metafiles.php');
            if ( !empty($metafiles[$module]['wizarddefs.php']) ) {
               $metadataFile = $metafiles[$module]['wizarddefs.php'];
               $foundViewDefs = true;
            }
         } elseif ( file_exists('modules/' . $module . '/metadata/metafiles.php') ) {
            require_once('modules/' . $module . '/metadata/metafiles.php');
            if ( !empty($metafiles[$module]['wizarddefs']) ) {
               $metadataFile = $metafiles[$module]['wizarddefs'];
               $foundViewDefs = true;
            }
         }
      }
      $GLOBALS['log']->debug("metadatafile=" . $metadataFile);
      if ( !$foundViewDefs && file_exists('modules/' . $module . '/metadata/wizarddefs.php') ) {
         $metadataFile = 'modules/' . $module . '/metadata/wizarddefs.php';
      }
      return $metadataFile;
   }

   function display() {
      $this->ev->process();
      echo $this->ev->display($this->showTitle, false);
   }

   public function getModuleTitle(
           $show_help = true
   ) {
      global $sugar_version, $sugar_flavor, $server_unique_key, $current_language, $action;

      $theTitle = "<div class='moduleTitle'>\n";

      $module = preg_replace("/ /", "", $this->module);

      $params = $this->_getModuleTitleParams();
      $index = 0;

      if ( SugarThemeRegistry::current()->directionality == "rtl" ) {
         $params = array_reverse($params);
      }
      if ( count($params) > 1 ) {
         array_shift($params);
      }
      $count = count($params);
      $paramString = '';
      foreach ( $params as $parm ) {
         $index++;
         $paramString .= $parm;
         if ( $index < $count ) {
            $paramString .= $this->getBreadCrumbSymbol();
         }
      }

      if ( !empty($paramString) ) {
         $theTitle .= "<h2> $paramString </h2>";

         if ( $this->type == "detail" ) {
            $theTitle .= "<div class='favorite' record_id='" . $this->bean->id . "' module='" . $this->bean->module_dir . "'><div class='favorite_icon_outline'>" . SugarThemeRegistry::current()->getImage('favorite-star-outline', 'title="' . translate('LBL_DASHLET_EDIT', 'Home') . '" border="0"  align="absmiddle"', null, null, '.gif', translate('LBL_DASHLET_EDIT', 'Home')) . "</div>
                                                    <div class='favorite_icon_fill'>" . SugarThemeRegistry::current()->getImage('favorite-star', 'title="' . translate('LBL_DASHLET_EDIT', 'Home') . '" border="0"  align="absmiddle"', null, null, '.gif', translate('LBL_DASHLET_EDIT', 'Home')) . "</div></div>";
         }
      }

      // bug 56131 - restore conditional so that link doesn't appear where it shouldn't
      if ( $show_help || $this->type == 'list' ) {
         $theTitle .= "<span class='utils'>";
         $createImageURL = SugarThemeRegistry::current()->getImageURL('create-record.gif');
         if ( $this->type == 'list' )
            $theTitle .= '<a href="#" class="btn btn-success showsearch"><span class=" glyphicon glyphicon-search" aria-hidden="true"></span></a>';$url = "index.php?module=$module&action=wizard&return_module=$module&return_action=DetailView";
         if ( $show_help ) {
            $theTitle .= <<<EOHTML
&nbsp;
<a id="create_image" href="{$url}" class="utilsLink">
<img src='{$createImageURL}' alt='{$GLOBALS['app_strings']['LNK_CREATE']}'></a>
<a id="create_link" href="{$url}" class="utilsLink">
{$GLOBALS['app_strings']['LNK_CREATE']}
</a>
EOHTML;
         }
         $theTitle .= "</span>";
      }

      $theTitle .= "<div class='clear'></div></div>\n";
      return $theTitle;
   }

}
