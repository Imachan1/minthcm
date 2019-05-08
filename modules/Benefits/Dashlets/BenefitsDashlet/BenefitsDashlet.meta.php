<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $app_strings;

$dashletMeta['BenefitsDashlet'] = array(
    'module' => 'Benefits',
    'title' => translate('LBL_HOMEPAGE_TITLE', 'Benefits'),
    'description' => 'A customizable view into Benefits',
    'category' => 'Module Views'
);