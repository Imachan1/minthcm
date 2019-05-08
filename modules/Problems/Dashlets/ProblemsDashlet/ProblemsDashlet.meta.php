<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $app_strings;

$dashletMeta['ProblemsDashlet'] = array(
   'module' => 'Problems',
   'title' => translate('LBL_HOMEPAGE_TITLE', 'Problems'),
   'description' => 'A customizable view into Problems',
   'category' => 'Module Views'
);
