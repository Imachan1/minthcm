<?php

$hook_version = 1;
$hook_array = Array();
$hook_array['before_relationship_add'] = Array();
$hook_array['before_relationship_add'][] = Array(
   101,
   'Force Relationship Policy',
   'modules/DashboardManager/logic_hooks/ForceDashboardRelationships.php',
   'ForceDashboardRelationships',
   'clearUserRelationshipsWithDM'
);

$hook_array['after_login'] = Array();
$hook_array['after_login'][] = Array(
   1,
   'SugarFeed old feed entry remover',
   'modules/SugarFeed/SugarFeedFlush.php',
   'SugarFeedFlush',
   'flushStaleEntries'
);

