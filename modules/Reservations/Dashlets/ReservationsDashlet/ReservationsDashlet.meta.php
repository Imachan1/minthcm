<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $app_strings;

$dashletMeta['ReservationsDashlet'] = array(
   'module' => 'Reservations',
   'title' => translate('LBL_HOMEPAGE_TITLE', 'Reservations'),
   'description' => 'A customizable view into Reservations',
   'category' => 'Module Views'
);
