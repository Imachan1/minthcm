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
                    $now = new DateTime('now', new DateTimeZone('UTC'));
                    $minutes = (int)$now->format('i');
                    $minutes = (int)(ceil($minutes / 15) * 15);
                    if ($minutes == 60) {
                        $now->modify('+1 hour');
                        $minutes = 0;
                    }
                    $now->setTime($now->format('H'), $minutes, 0);
                    return [
                        'date_start' => $now->format('Y-m-d H:i:s'),
                        'date_end' => $now->modify('+15 minutes')->format('Y-m-d H:i:s'),
                    ];
                }
            ]
        ],
    ],
];
