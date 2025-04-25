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
                ]
            ],
        ],
        'hired' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['status'],
            'trigger' => Formula::inArray('$status', ['Acceptance','Hired']),
            'logic' => [
                'visible' => [
                    'work_start' => true,
                    'training_date' => true,
                ]
            ],
        ],
        'rejection' => [
            'hooks' => [Hook::ALL, Hook::CHANGE],
            'triggerFields' => ['status'],
            'trigger' => Formula::inArray('$status', ['Rejected']),
            'logic' => [
                'visible' => [
                    'reason_for_rejection' => true,
                ]
            ],
        ],
    ],
];
