<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
if(!isset($hook_array)){ $hook_array = Array();} 
// position, file, function 
if(!isset($hook_array['before_save'])){ $hook_array['before_save'] = Array(); }
$hook_array['before_save'][] = Array(77, 'updateGeocodeInfo', 'modules/Project/ProjectJjwg_MapsLogicHook.php','ProjectJjwg_MapsLogicHook', 'updateGeocodeInfo'); 
if(!isset($hook_array['after_save'])){ $hook_array['after_save'] = Array(); }
$hook_array['after_save'][] = Array(77, 'updateRelatedMeetingsGeocodeInfo', 'modules/Project/ProjectJjwg_MapsLogicHook.php','ProjectJjwg_MapsLogicHook', 'updateRelatedMeetingsGeocodeInfo'); 
if(!isset($hook_array['after_relationship_add'])){ $hook_array['after_relationship_add'] = Array(); }
$hook_array['after_relationship_add'][] = Array(77, 'addRelationship', 'modules/Project/ProjectJjwg_MapsLogicHook.php','ProjectJjwg_MapsLogicHook', 'addRelationship'); 
if(!isset($hook_array['after_relationship_delete'])){ $hook_array['after_relationship_delete'] = Array(); }
$hook_array['after_relationship_delete'][] = Array(77, 'deleteRelationship', 'modules/Project/ProjectJjwg_MapsLogicHook.php','ProjectJjwg_MapsLogicHook', 'deleteRelationship'); 



?>