<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $app_strings;

$dashletMeta['ConclusionsDashlet'] = array(
    'module' => 'Conclusions',
    'title' => translate('LBL_HOMEPAGE_TITLE', 'Conclusions'),
    'description' => 'A customizable view into Conclusions',
    'category' => 'Module Views'
);