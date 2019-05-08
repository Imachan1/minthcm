<?php

require_once 'include/MVC/View/views/view.list.php';

class KTemplatesViewList extends ViewList {

   /**
    * Return the "breadcrumbs" to display at the top of the page
    *
    * @param  bool $show_help optional, true if we show the help links
    * @return HTML string containing breadcrumb title
    */
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
