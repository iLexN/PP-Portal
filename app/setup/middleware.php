<?php

//by route
//$checkToken = new \PP\Middleware\CheckToken($container);

//all app
if ( $dbSetting['logging'] ){
    $app->add(new \PP\Middleware\DBLog($container,$capsule));
}
//$app->add(new \PP\Middleware\RouteLog($container));
//$app->add(new \PP\Middleware\Firewall($container));
$app->add(new \PP\Middleware\HttpBasicAuth($container));
$app->add(new \PP\Middleware\CheckPlatform($container));
