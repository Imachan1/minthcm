<?php

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        'init' => [
            'hooks' => [Hook::ALL],
            'logic' => [
                'visible' => [
                    'delegation_duration' => false,
                    'occasional_leave_type' => false,
                    'comments' => false,
                ],
                'required' => [
                    'delegation_duration' => false,
                    'occasional_leave_type' => false,
                    'comments' => false,
                ],
            ],
        ],
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::equals('$type', 'delegation'),
            'logic' => [
                'visible' => [
                    'delegation_duration' => true,
                ],
                'required' => [
                    'delegation_duration' => true,
                ],
                'validation' => [
                    'delegation_duration' => [
                        function ($bean) {
                            if (empty($bean->delegation_duration)) {
                                throw new ValidationException('Delegation duration is required.');
                            }
                        },
                    ],
                ],
            ],
        ],

        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::equals('$type', 'occasional_leave'),
            'logic' => [
                'visible' => [
                    'occasional_leave_type' => true,
                ],
                'required' => [
                    'occasional_leave_type' => true,
                ],
                'validation' => [
                    'occasional_leave_type' => [
                        function ($bean) {
                            if (empty($bean->occasional_leave_type)) {
                                throw new ValidationException('Occasional leave type is required.');
                            }
                        },
                    ],
                ],
            ],
        ],
        [
            'hooks' => [Hook::INIT, Hook::CHANGE],
            'triggerFields' => ['type'],
            'trigger' => Formula::or(
                Formula::equals('$type', 'occasional_leave'),
                Formula::equals('$type', 'excused_absence')
            ),
            'logic' => [
                'visible' => [
                    'comments' => true,
                ],
                'required' => [
                    'comments' => true,
                ],
                'validation' => [
                    'comments' => [
                        function ($bean) {
                            if (empty($bean->comments)) {
                                throw new ValidationException('Comment is required for this type.');
                            }
                        },
                    ],
                ],
            ],
        ],
    ],
];
