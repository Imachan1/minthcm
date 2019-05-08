<?php

$popupMeta = array(
   'moduleMain' => 'UsersNews',
   'varName' => 'UsersNews',
   'orderBy' => 'usersnews.name',
   'whereClauses' => array(
      'name' => 'usersnews.name',
      'news_name' => 'usersnews.news_name',
      'news_read' => 'usersnews.news_read',
      'not_display' => 'usersnews.not_display',
      'assigned_user_name' => 'usersnews.assigned_user_name',
   ),
   'searchInputs' => array(
      'name',
      'news_name',
      'news_read',
      'not_display',
      'assigned_user_name',
   ),
   'searchdefs' => array(
      'name' =>
      array(
         'name' => 'name',
         'width' => '10%',
      ),
      'news_name' =>
      array(
         'link' => true,
         'type' => 'relate',
         'label' => 'LBL_NEWS_NAME',
         'id' => 'NEWS_ID',
         'width' => '10%',
         'name' => 'news_name',
      ),
      'news_read' =>
      array(
         'type' => 'bool',
         'label' => 'LBL_NEWS_READ',
         'width' => '10%',
         'name' => 'news_read',
      ),
      'not_display' =>
      array(
         'type' => 'date',
         'label' => 'LBL_NOT_DISPLAY',
         'width' => '10%',
         'name' => 'not_display',
      ),
      'assigned_user_name' =>
      array(
         'link' => true,
         'type' => 'relate',
         'label' => 'LBL_ASSIGNED_TO_NAME',
         'id' => 'ASSIGNED_USER_ID',
         'width' => '10%',
         'name' => 'assigned_user_name',
      ),
   ),
);
