<?php

//by route
//$checkToken = new \PP\Middleware\CheckToken($container);

//all app
if ( $dbSetting['logging'] ){
    $app->add(function($request, $response, $next) use ($capsule) {
        $response = $next($request, $response);
        $query = $capsule->getConnection()->getQueryLog();
        $this->logger->info('query',$query);

        return $response;
    });
}
//$app->add(new \PP\Middleware\RouteLog($container));
//$app->add(new \PP\Middleware\Firewall($container));
$app->add(new \PP\Middleware\HttpBasicAuth($container));
$app->add(new \PP\Middleware\CheckPlatform($container));
