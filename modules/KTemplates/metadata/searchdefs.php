<?php

$module_name = 'KTemplates';
$searchdefs [$module_name] = array(
   'layout' =>
   array(
      'basic_search' =>
      array(
         'name' =>
         array(
            'name' => 'name',
            'default' => true,
            'width' => '10%',
         ),
      ),
      'advanced_search' =>
      array(
         'name' =>
         array(
            'name' => 'name',
            'default' => true,
            'width' => '10%',
         ),
         'is_default' =>
         array(
            'type' => 'bool',
            'default' => true,
            'label' => 'LBL_IS_DEFAULT',
            'width' => '10%',
            'name' => 'is_default',
         ),
         'relatedmodule' =>
         array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_RELATEDMODULE',
            'width' => '10%',
            'name' => 'relatedmodule',
            'function' =>
            array(
               'name' => 'getKReportsArrayList',
               'params' =>
               array(
                  null,
                  'relatedmodule',
                  null,
                  'SearchView',
               ),
            ),
         ),
      ),
   ),
   'templateMeta' =>
   array(
      'maxColumns' => '3',
      'widths' =>
      array(
         'label' => '10',
         'field' => '30',
      ),
   ),
);
?>
