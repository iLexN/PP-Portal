<?php
return [
    'settings' => [
        'displayErrorDetails' => true,
        //'routerCacheDisabled' => false,
        //'routerCacheFile' => __DIR__ . '/../cache/route.cache',
        "determineRouteBeforeAppMiddleware" => true,

        'systemMessage' => __DIR__ . '/../response-message/',
        'WebPortal' => [
            'url' => 'http://portal.pacificprime.com'
        ],
    ]
];


