<?php
//Last In First Executed

//by route
//$checkToken = new \PP\Middleware\CheckToken($container);
$checkUserExist = new \PP\Portal\Middleware\CheckUserExist($container);

//all app
if ($dbSetting['logging']) {
    $app->add(new \PP\Portal\Middleware\DBLog($container, $capsule));
}
//$app->add(new \PP\Middleware\RouteLog($container));
//$app->add(new \PP\Middleware\Firewall($container));
$app->add(new \PP\Portal\Middleware\HttpBasicAuth($container));
$app->add(new \PP\Portal\Middleware\CheckPlatform($container));
