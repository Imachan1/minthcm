<?php

use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        'init' => [
            'hooks' => [Hook::ALL],
            'logic' => [
                'visible' => [
                    'work_start' => false,
                    'training_date' => false,
                    'reason_for_rejection' => false,
                ],
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
        'hired' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['status'],
            'trigger' => Formula::inArray('$status', ['Acceptance', 'Hired']),
            'logic' => [
                'visible' => [
                    'work_start' => true,
                    'training_date' => true,
                ],
            ],
        ],
        'rejection' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['status'],
            'trigger' => Formula::inArray('$status', ['Rejected']),
            'logic' => [
                'visible' => [
                    'reason_for_rejection' => true,
                ],
            ],
        ],
    ],
];
