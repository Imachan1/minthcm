<?php

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        'init' => [
            'hooks' => [Hook::INIT],
            'logic' => [
                'update' => function ($bean) {
                    global $current_user; /** @var User $current_user */
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
