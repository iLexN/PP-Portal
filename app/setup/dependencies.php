<?php

$container = $app->getContainer();




// monolog
$container['logger'] = function (\Slim\Container $c) {
    $settings = $c->get('logConfig');
    $logger = new \Monolog\Logger($settings['name']);
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], \Monolog\Logger::DEBUG));
    //$logger->pushHandler(new Monolog\Handler\NativeMailerHandler($settings['mailTo'],$settings['mailSubject'],$settings['mailFrom']));
    //$logger->pushHandler(new \Monolog\Handler\BrowserConsoleHandler());
    return $logger;
};

//data cache
$container['pool'] = function (\Slim\Container $c) {
    $settings = $c->get('dataCacheConfig');
    $driver = new \Stash\Driver\FileSystem($settings);

    return new \Stash\Pool($driver);
};

// rount handloer
$container['notFoundHandler'] = function (\Slim\Container $c) {
    return function (\Slim\Http\Request $request, \Slim\Http\Response  $response) use ($c) {
        $logInfo = [
            'method' => $request->getMethod(),
            'uri'    => (string) $request->getUri(),
        ];
        $c->logger->info('404', $logInfo);

        return $c['response']->withStatus(404)
                ->write(404);
    };
};

if ( !$container['settings']['displayErrorDetails']){
    $container['errorHandler'] = function (\Slim\Container $c) {
        return function (\Slim\Http\Request $request, \Slim\Http\Response $response, \Exception $exception) use ($c) {
            $c['logger']->error('e',(array)$exception);

            return $c['response']->withStatus(500)
                                 ->withHeader('Content-Type', 'text/html')
                                 ->write('Something went wrong!');
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