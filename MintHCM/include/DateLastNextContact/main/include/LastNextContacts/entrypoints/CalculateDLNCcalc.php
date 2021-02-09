<?php

require_once 'custom/include/LastNextContacts/LastNextContacts.php';
require_once 'custom/include/evSubscription/DateLastNextContactSubscription.php';
global $current_user;

if (!DateLastNextContactSubscription::validateKey()) {
    exit("Warning: eVolpe Subscription for Last Next Contact is invalid or inactive, check its status in the Subscription Management section.");
}
if ($current_user->id != "1") {
    exit("Error: Access denied.");
}
if (!isset($_POST['module_name']) || !isset($_POST['null_records']) || !isset($_POST['return_only_count'])) {
    exit(json_encode(["status" => false, "post_data" => $_POST]));
}

$module = $_POST['module_name'];

if (!in_array($module, ['leads', 'accounts', 'contacts', 'prospects'])) {
    exit(json_encode(["status" => false, "post_data" => $_POST]));
}

$part_size = 10;

$db = DBManagerFactory::getInstance();
$data = ["status" => true];
$only_null = $_POST['null_records'] == 'true';
$only_count = $_POST['return_only_count'] == 'true';
$part = $_POST['part'] ?? 0;
$part = $part * $part_size;

if ($only_count) {
    if ($only_null) {
        $query = "SELECT COUNT(id) FROM {$module} WHERE (last_time_contact is null OR date_planned_contact is null) AND deleted = 0";
    } else {
        $query = "SELECT COUNT(id) FROM {$module} WHERE deleted = 0";
    }
    $data = array_merge($data, ['value' => $db->getOne($query)]);
} else {
    $dlnc = new LastNextContacts();
    if ($only_null) {
        $query = "SELECT id FROM {$module} WHERE (last_time_contact is null OR date_planned_contact is null) AND deleted = 0 ORDER BY date_entered ASC LIMIT {$part}, {$part_size}";
    } else {
        $query = "SELECT id FROM {$module} WHERE deleted = 0 ORDER BY date_entered ASC LIMIT {$part}, {$part_size}";
    }
    $result = $db->query($query);
    while ($row = $db->fetchByAssoc($result)) {
        $dlnc->updateBean(["related_id" => $row['id'], "related_module" => ucfirst($module)]);
    }
}
echo json_encode($data);
