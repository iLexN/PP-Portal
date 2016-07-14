<?php

$container = $app->getContainer();

// monolog
$container['logger'] = function (\Slim\Container $c) {
    $settings = $c->get('logConfig');
    $logger = new \Monolog\Logger($settings['name']);
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], \Monolog\Logger::DEBUG));
    if (!$c['settings']['displayErrorDetails']) {
        $logger->pushHandler(new Monolog\Handler\NativeMailerHandler($settings['mailTo'], $settings['mailSubject'], $settings['mailFrom']));
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
    if (!$c['settings']['displayErrorDetails']) {
        $view->addExtension(new Twig_Extension_Debug());
    }

    return $view;
};

$container['httpClient'] = function () {
    return new \GuzzleHttp\Client();
};

// rount handloer
$container['notFoundHandler'] = function (\Slim\Container $c) {
    return function (\Slim\Http\Request $request, \Slim\Http\Response  $response) use ($c) {
        $logInfo = [
            'method' => $request->getMethod(),
            'uri'    => (string) $request->getUri(),
        ];
        $c->logger->info('404', $logInfo);

        return $c['ViewHelper']->toJson($response, ['errors' => 
                    $c['msgCode'][1510]
                ])->withStatus(404);
    };
};

if (!$container['settings']['displayErrorDetails']) {
    $container['errorHandler'] = function (\Slim\Container $c) {
        return function (\Slim\Http\Request $request, \Slim\Http\Response $response, \Exception $exception) use ($c) {
            $c['logger']->error('e', (array) $exception);

            return $c['ViewHelper']->toJson($response, ['errors' => 
                    $c['msgCode'][1520]
                ])->withStatus(500);
        };
    };
}
$container['mailer'] = function () {
    return new \PHPMailer();
};

$container['UserModule'] = function (\Slim\Container $c) {
    return new \PP\Portal\Module\UserModule($c);
};

$container['PasswordModule'] = function (\Slim\Container $c) {
    return new \PP\Portal\Module\PasswordModule($c);
};

$container['ViewHelper'] = function (\Slim\Container $c) {
    return new \PP\Portal\Module\Helper\View($c);
};

$container['msgCode'] = function (\Slim\Container $c) {
    return require $c['settings']['systemMessage'] . 'en.php';
};