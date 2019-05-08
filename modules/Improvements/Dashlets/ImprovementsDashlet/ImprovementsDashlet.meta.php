<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $app_strings;

$dashletMeta['ImprovementsDashlet'] = array(
   'module' => 'Improvements',
   'title' => translate('LBL_HOMEPAGE_TITLE', 'Improvements'),
   'description' => 'A customizable view into Improvements',
   'category' => 'Module Views'
);
