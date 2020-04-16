<?php

function getMeetingTypes()
{
    global $db;
    $sql = "SELECT id, name FROM dictionaries WHERE list_type LIKE 'Meetings' AND is_active = 1 AND deleted = 0";
    $types = array();
    $result = $db->query($sql);
    while (($row = $db->fetchByAssoc($result)) != null) {
        $types[$result['name']] = $result['name'];
    }

    return $types;
}
