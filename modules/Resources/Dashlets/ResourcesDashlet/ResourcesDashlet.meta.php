<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $app_strings;

$dashletMeta['ResourcesDashlet'] = array(
   'module' => 'Resources',
   'title' => translate('LBL_HOMEPAGE_TITLE', 'Resources'),
   'description' => 'A customizable view into Resources',
   'category' => 'Module Views'
);
