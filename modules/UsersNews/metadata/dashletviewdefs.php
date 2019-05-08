<?php

$dashletData['UsersNewsDashlet']['searchFields'] = array(
   'name' =>
   array(
      'default' => '',
   ),
   'news_name' =>
   array(
      'default' => '',
   ),
   'news_read' =>
   array(
      'default' => '',
   ),
   'not_display' =>
   array(
      'default' => '',
   ),
   'date_entered' =>
   array(
      'default' => '',
   ),
   'date_modified' =>
   array(
      'default' => '',
   ),
   'assigned_user_name' =>
   array(
      'default' => '',
   ),
);
$dashletData['UsersNewsDashlet']['columns'] = array(
   'name' =>
   array(
      'width' => '40%',
      'label' => 'LBL_LIST_NAME',
      'link' => true,
      'default' => true,
      'name' => 'name',
   ),
   'news_name' =>
   array(
      'name' => 'news_name',
      'default' => true,
      'label' => 'LBL_NEWS_NAME',
      'width' => '10%',
   ),
   'news_read' =>
   array(
      'type' => 'bool',
      'default' => true,
      'label' => 'LBL_NEWS_READ',
      'width' => '10%',
   ),
   'not_display' =>
   array(
      'type' => 'date',
      'label' => 'LBL_NOT_DISPLAY',
      'width' => '10%',
      'default' => true,
   ),
   'assigned_user_name' =>
   array(
      'width' => '8%',
      'label' => 'LBL_LIST_ASSIGNED_USER',
      'name' => 'assigned_user_name',
      'default' => true,
   ),
   'date_entered' =>
   array(
      'width' => '15%',
      'label' => 'LBL_DATE_ENTERED',
      'default' => false,
      'name' => 'date_entered',
   ),
   'date_modified' =>
   array(
      'width' => '15%',
      'label' => 'LBL_DATE_MODIFIED',
      'name' => 'date_modified',
      'default' => false,
   ),
   'created_by' =>
   array(
      'width' => '8%',
      'label' => 'LBL_CREATED',
      'name' => 'created_by',
      'default' => false,
   ),
);
