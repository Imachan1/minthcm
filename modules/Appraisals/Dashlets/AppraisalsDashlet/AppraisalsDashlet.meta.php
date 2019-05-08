<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $app_strings;

$dashletMeta['AppraisalsDashlet'] = array(
   'module' => 'Appraisals',
   'title' => translate('LBL_HOMEPAGE_TITLE', 'Appraisals'),
   'description' => 'A customizable view into Appraisals',
   'category' => 'Module Views'
);
