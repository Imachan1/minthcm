<?php

$hook_types_array = array(
    'after_relationship_add',
    'before_save',
    'after_relationship_delete',
    'before_relationship_delete',
    'after_save',
    'after_delete',
    'after_email_import',
);

if (!isset($hook_array)) {
    $hook_array = array();
}
foreach ($hook_types_array as $type) {
    if (!isset($hook_array[$type])) {
        $hook_array[$type] = array();
    }
}

$hook_array['after_relationship_add'][] = array(20,
    'update l&n call - calls - ara',
    'custom/include/LastNextContacts/LastNextContactsHooks.php',
    'LastNextContactsHooks',
    'afterRelationshipAdd',
);
$hook_array['after_relationship_delete'][] = array(20,
    'update l&n call - calls - ard',
    'custom/include/LastNextContacts/LastNextContactsHooks.php',
    'LastNextContactsHooks',
    'afterRelationshipDelete',
);
$hook_array['before_relationship_delete'][] = array(20,
    'update l&n call - calls - brd',
    'custom/include/LastNextContacts/LastNextContactsHooks.php',
    'LastNextContactsHooks',
    'storeRelations',
);

$hook_array['after_save'][] = array(20,
    'update l&n call - calls - as',
    'custom/include/LastNextContacts/LastNextContactsHooks.php',
    'LastNextContactsHooks',
    'afterBeanUpdate',
);
$hook_array['after_delete'][] = array(20,
    'update l&n call - calls - ad',
    'custom/include/LastNextContacts/LastNextContactsHooks.php',
    'LastNextContactsHooks',
    'afterBeanDelete',
);
$hook_array['after_email_import'][] = array(20,
    'update l&n call - emails - aei',
    'custom/include/LastNextContacts/LastNextContactsHooks.php',
    'LastNextContactsHooks',
    'afterEmailImport',
);
