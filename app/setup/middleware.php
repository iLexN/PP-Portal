<?php

//Last In First Executed

//by route
$checkUserExist = new \PP\Portal\Middleware\CheckUserExist($container);
$checkUsePolicyrExist = new \PP\Portal\Middleware\CheckUsePolicyrExist($container);
$checkClaimExist = new \PP\Portal\Middleware\CheckClaimExist($container);

//all app
if ($dbSetting['logging']) {
    $app->add(new \PP\Portal\Middleware\DBLog($container, $capsule));
}

$app->add(new \PP\Portal\Middleware\HttpBasicAuth($container));
$app->add(new \PP\Portal\Middleware\CheckPlatform($container));
