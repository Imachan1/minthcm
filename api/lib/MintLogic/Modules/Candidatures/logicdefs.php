<?php

use MintHCM\Lib\MintLogic\Formula;
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
        'notHired' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['status'],
            'trigger' => Formula::notInArray('$status', ['Acceptance', 'Hired']),
            'logic' => [
                'visible' => [
                    'work_start' => false,
                    'training_date' => false,
                ],
            ],
        ],
        'rejection' => [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['status'],
            'trigger' => Formula::notInArray('$status', ['Rejected']),
            'logic' => [
                'visible' => [
                    'reason_for_rejection' => false,
                ],
            ],
        ],
        'originalCandidature' => [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['route_of_acquisition'],
            'trigger' => Formula::inArray('$route_of_acquisition', ['', 'original_candidature']),
            'logic' => [
                'visible' => [
                    'original_candidature_name' => false,
                ],
            ],
        ],
    ],
];
