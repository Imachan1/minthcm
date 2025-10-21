<?php

use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
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
        [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['room_surface'],
            'trigger' => true,
            'logic' => [
                'validation' => [
                    'room_surface' => [
                        function ($bean) {
                            /** @var Rooms $bean */
                            $value = $bean->room_surface;
                            if (empty($value) || floatval($value) <= 0) {
                                throw new ValidationException('LBL_NEGATIVE_SURFACE');
                            }
                        },
                    ],
                ],
            ],
        ],
        [
            'hooks' => [Hook::ALL],
            'triggerFields' => ['security_group_id'],
            'trigger' => Formula::notEmpty('$security_group_id'),
            'logic' => [
                'validation' => [
                    'security_group_name' => [
                        function ($bean) {
                            /** @var SecurityGroup $group */
                            $group = \BeanFactory::getBean('SecurityGroups', $bean->security_group_id);
                            if (empty($group->id) || $group->group_type !== 'business_unit') {
                                throw new ValidationException('LBL_ERR_CANT_SELECT_SEC_GROUP');
                            }
                        },
                    ],
                ],
            ],
        ],

    ],
];
