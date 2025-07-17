<?php

use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['assigned_user_id'],
            'trigger' => Formula::empty('$assigned_user_id'),
            'logic' => [
                'update' => function ($bean) {
                    global $current_user;
                    if (empty($bean->assigned_user_id)) {
                        return [
                            'assigned_user_id' => $current_user->id,
                            'assigned_user_name' => $current_user->name,
                        ];
                    }
                    return [];
                },
            ],
        ],
    ],
    
];