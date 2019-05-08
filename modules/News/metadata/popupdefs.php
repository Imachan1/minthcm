<?php

$popupMeta = array(
   'moduleMain' => 'News',
   'varName' => 'News',
   'orderBy' => 'news.name',
   'whereClauses' => array(
      'name' => 'news.name',
      'news_type' => 'news.news_type',
      'assigned_user_name' => 'news.assigned_user_name',
      'news_status' => 'news.news_status',
   ),
   'searchInputs' => array(
      'name',
      'news_type',
      'assigned_user_name',
      'news_status',
   ),
   'searchdefs' => array(
      'name' =>
      array(
         'name' => 'name',
         'width' => '10%',
      ),
      'news_type' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_NEWS_TYPE',
         'width' => '10%',
         'name' => 'news_type',
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
      'news_status' =>
      array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_NEWS_STATUS',
         'width' => '10%',
         'name' => 'news_status',
      ),
   ),
);
