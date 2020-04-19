<?php

function getDictionary($params)
{
    global $db;
    $field = $params['key'];
    $sql = "SELECT id, name FROM dictionaries WHERE list_type LIKE '{$field}' AND is_active = 1 AND deleted = 0";
    $types = array();
    $result = $db->query($sql);
    while (($row = $db->fetchByAssoc($result)) != null) {
        $types[$result['name']] = $result['name'];
    }

    return $types;
}
