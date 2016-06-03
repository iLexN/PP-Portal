<?php

session_start();

$autoloader = require '../vendor/autoload.php';
$autoloader->addPsr4('PP\Portal\Controller\\', __DIR__.'/../app/Controller');
$autoloader->addPsr4('PP\Portal\dbModel\\', __DIR__.'/../app/DB-Model');
$autoloader->addPsr4('PP\Module\\', __DIR__.'/../app/Module');
$autoloader->addPsr4('PP\Middleware\\', __DIR__.'/../app/Middleware');

date_default_timezone_set('Asia/Hong_Kong');

// get config
$conf = new Noodlehaus\Config(__DIR__.'/../app/config');
$settings = $conf->all() ?: [];

// app init
$app = new \Slim\App($settings);

// service setup
require __DIR__.'/../app/setup/dependencies.php';

//db setup
require __DIR__.'/../app/setup/db-setup.php';

//Middleware
require __DIR__.'/../app/setup/middleware.php';

require __DIR__.'/../app/route/route.php';

// Run!
$app->run();
