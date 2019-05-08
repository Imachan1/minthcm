<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $app_strings;

$dashletMeta['ResponsibilitiesDashlet'] = array(
    'module' => 'Responsibilities',
    'title' => translate('LBL_HOMEPAGE_TITLE', 'Responsibilities'),
    'description' => 'A customizable view into Responsibilities',
    'category' => 'Module Views'
);