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
        [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['date_start', 'date_end'],
            'trigger' => true,
            'logic' => [
                'validation' => [
                    'date_start' => [
                        function ($bean) {
                            $date_start = empty($bean->date_start) ? null : new DateTime($bean->date_start);
                            $date_end = empty($bean->date_end) ? null : new DateTime($bean->date_end);
                            if ($date_end && $date_start > $date_end) {
                                throw new ValidationException(translate('LBL_DATE_START', 'ExitInterviews') . ' ' . translate('MSG_IS_NOT_BEFORE') . ' ' . translate('LBL_DATE_END', 'ExitInterviews'));
                            }
                        },
                    ],
                ],
            ],
        ],
    ],
];
