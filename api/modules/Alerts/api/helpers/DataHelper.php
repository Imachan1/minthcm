<?php

namespace MintHCM\Modules\Alerts\api\helpers;

use Alert;

class DataHelper
{
    public static function getNewAlerts()
    {
        chdir('../legacy/');
        require_once "modules/Alerts/controller.php";
        $controller = new \AlertsController();
        $controller->action_get();
        $alerts = $controller->view_object_map['Results'];
        $data = [];
        foreach ($alerts as $index => $alert) {
            if (!empty($alert) && $alert instanceof Alert) {
                if (false == $alert->is_read && false == $alert->is_closed) {
                    $data[] = $alert->toArray();
                }
            }
        }
        chdir('../api/');
        return $data;
    }

    public static function isAssignedUserCurrentUser(Alert $alert)
    {
        global $current_user;
        return $alert->assigned_user_id === $current_user->id;
    }
}
