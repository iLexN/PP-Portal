<?php

$container = $app->getContainer();




// monolog
$container['logger'] = function (\Slim\Container $c) {
    $settings = $c->get('logConfig');
    $logger = new \Monolog\Logger($settings['name']);
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], \Monolog\Logger::DEBUG));
    if ( !$c['settings']['displayErrorDetails']){
        $logger->pushHandler(new Monolog\Handler\NativeMailerHandler($settings['mailTo'],$settings['mailSubject'],$settings['mailFrom']));
    }
    //$logger->pushHandler(new \Monolog\Handler\BrowserConsoleHandler());
    return $logger;
};

//data cache
$container['pool'] = function (\Slim\Container $c) {
    $settings = $c->get('dataCacheConfig');
    $driver = new \Stash\Driver\FileSystem($settings);

    return new \Stash\Pool($driver);
};

// Twig
$container['twigView'] = function (\Slim\Container $c) {
    $settings = $c->get('twigConfig');
    $view = new \Slim\Views\Twig($settings['template_path'], $settings['twig']);
    if ( !$c['settings']['displayErrorDetails']){
        $view->addExtension(new Twig_Extension_Debug());
    }
    return $view;
};

// rount handloer
$container['notFoundHandler'] = function (\Slim\Container $c) {
    return function (\Slim\Http\Request $request, \Slim\Http\Response  $response) use ($c) {
        $logInfo = [
            'method' => $request->getMethod(),
            'uri'    => (string) $request->getUri(),
        ];
        $c->logger->info('404', $logInfo);

        return $c['view']->render($request, $response, ['error'=>[
                    'status'=>404,
                    'title'=>'not found'
                ]])->withStatus(404);
    };
};

if ( !$container['settings']['displayErrorDetails']){
    $container['errorHandler'] = function (\Slim\Container $c) {
        return function (\Slim\Http\Request $request, \Slim\Http\Response $response, \Exception $exception) use ($c) {
            $c['logger']->error('e',(array)$exception);

            return $c['view']->render($request, $response, ['error'=>[
                    'status'=>500,
                    'title'=>'error happen'
                ]])->withStatus(500);
        };
    };
}

$container['dataManager'] = function (){
    return new \League\Fractal\Manager();
};

$container['view'] = function (){
    return new RKA\ContentTypeRenderer\Renderer();
};

$container['UserModule'] = function (\Slim\Container $c){
    return new \PP\Module\UserModule($c);
};
