<?php
return [
    'logConfig'=>[
            'name' => 'app',
            'path' => __DIR__ . '/../../logs/'.date("Y-m-d").'.log',
            //'path' => __DIR__ . '/../logs/app.log',
            'mailFrom' => 'alex@kwiksure.com',
            'mailTo' => 'alex@kwiksure.com',
            'mailSubject' => 'claims error log',
    ],
];
