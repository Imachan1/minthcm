<?php

use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
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
        'recruitments' => [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['recruitment_name'],
            'trigger' => Formula::notEmpty('$recruitment_name'),
            'logic' => [
                'update' => function ($bean) {
                    if (empty($bean->recruitment_end_name) && !empty($bean->recruitment_name)) {
                        return [
                            'recruitment_end_id' => $bean->recruitment_id,
                            'recruitment_end_name' => $bean->recruitment_name,
                        ];
                    }
                    return [];
                },
            ],
        ],
    ],
];
