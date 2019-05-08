<?php

function displayInlineAppraisalItems($focus, $field, $value, $view) {
   global $app_strings, $app_list_strings;
   $appraisal_items = array();
   $view_map = [
      'EditView' => 'modules/AppraisalItems/LineItems/LineItemsEditView.tpl',
      'DetailView' => 'modules/AppraisalItems/LineItems/LineItemsDetailView.tpl'
   ];

   if ( !empty($focus->id) ) {
      $query = "SELECT id, name, value, parent_type, parent_id, description FROM appraisalitems WHERE appraisal_id = '{$focus->id}' AND deleted = 0";
      $result = $focus->db->query($query);

      while ( $row = $focus->db->fetchByAssoc($result) ) {
         $parent_bean = BeanFactory::getBean($row['parent_type']);
         if ( $parent_bean->retrieve($row['parent_id']) ) {
            $row['parent_name'] = $parent_bean->name;
         }
         $appraisal_items[] = ( $view == 'DetailView' ) ? $row : json_encode($row);
      }
   }

   $sugar_smarty = new Sugar_Smarty();
   $sugar_smarty->assign('APP', $app_strings);
   $sugar_smarty->assign('APP_LIST_STRINGS', $app_list_strings);
   $sugar_smarty->assign('APPRAISAL_ITEMS', $appraisal_items);

   return array_key_exists($view, $view_map) ? $sugar_smarty->fetch($view_map[$view]) : null;
}
