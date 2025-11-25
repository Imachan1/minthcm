<?php

use MintHCM\Lib\MintLogic\Formula;
use MintHCM\Lib\MintLogic\Hook;

return [
    'rules' => [
        'init' => [
            'hooks' => [Hook::INIT],
            'trigger' => Formula::empty('$id'),
            'logic' => [
                'update' => function ($bean) {
                    if (!empty($bean->date_start) || !empty($bean->date_end)) {
                        return [];
                    }
                    $now = new \DateTime();
                    $now->setTime($now->format('H'), 0, 0);
                    return [
                        'date_start' => $now->modify('+1 hour')->format('Y-m-d H:i:s'),
                        'date_end' => $now->modify('+1 hour')->format('Y-m-d H:i:s'),
                    ];
                }
            ]
        ],
    ],
];
