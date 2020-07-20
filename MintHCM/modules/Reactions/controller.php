<?php
require_once 'include/MVC/Controller/SugarController.php';

class ReactionsController extends SugarController
{

    public static function getReactionForRecord($module_name, $record_id)
    {
        global $db, $current_user;
        $reactions = static::getReactionsTypes();
        $sql = "
            SELECT r.reaction_type reaction, IFNULL(CONCAT(u.first_name,' ',u.last_name), u.last_name) user_name, u.id user_id FROM reactions r
            INNER JOIN users u ON u.id = r.assigned_user_id AND u.status = 'Active' AND u.deleted = 0
            WHERE r.parent_type = '{$module_name}' AND r.parent_id = '{$record_id}' AND r.active = 1 AND r.deleted = 0
        ";
        $result = $db->query($sql);
        while (list('reaction' => $reaction, 'user_name' => $user_name, 'user_id' => $user_id) = $db->fetchByAssoc($result)) {
            if ($user_id === $current_user->id) {
                $reactions[$reaction]['active'] = true;
            }
            $reactions[$reaction]['users'][$user_id] = $user_name;
        }
        return $reactions;
    }

    public static function getReactionsTypes()
    {
        global $app_list_strings;
        $reactions_types = [];
        foreach ($app_list_strings['reaction_type_list'] as $key => $value) {
            $reactions_types[$key] = [
                'active' => false,
                'users' => [],
            ];
        }
        return $reactions_types;
    }

    public static function addReaction($reaction_type, $module_name, $record_id)
    {
        global $db, $current_user;
        $result = false;
        $sql = "
            SELECT id FROM reactions
            WHERE
                parent_type = '{$module_name}'
                AND parent_id = '{$record_id}'
                AND assigned_user_id = '{$current_user->id}'
                AND reaction_type = '{$reaction_type}'
                AND deleted = 0
        ";
        $reaction_id = $db->getOne($sql);
        $reaction = BeanFactory::getBean('Reactions', $reaction_id);
        $reaction->parent_type = $module_name;
        $reaction->parent_id = $record_id;
        $reaction->assigned_user_id = $current_user->id;
        $reaction->active = 1;
        $reaction->reaction_type = $reaction_type;
        if ($reaction->save(false)) {
            $result = [
                'id' => $current_user->id,
                'name' => $current_user->full_name,
            ];
        }
        return $result;
    }

    public static function removeReaction($reaction_type, $module_name, $record_id)
    {
        global $db, $current_user;
        $result = false;
        $sql = "
            UPDATE reactions
            SET active = 0
            WHERE
                parent_type = '{$module_name}'
                AND parent_id = '{$record_id}'
                AND assigned_user_id = '{$current_user->id}'
                AND reaction_type = '{$reaction_type}'
                AND deleted = 0
        ";
        if ($db->query($sql)) {
            $result = $current_user->id;
        }
        return $result;
    }
}
