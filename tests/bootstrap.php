<?php

$autoloader = require dirname(__DIR__) . '/vendor/autoload.php';

$autoloader->addPsr4('PP\Portal\Controller\\', __DIR__.'/../src/Controller');
$autoloader->addPsr4('PP\Portal\dbModel\\', __DIR__.'/../src/DB-Model');
$autoloader->addPsr4('PP\Module\\', __DIR__.'/../src/Module');
$autoloader->addPsr4('PP\Middleware\\', __DIR__.'/../src/Middleware');

$db = [
         'driver'   => 'sqlite',
         'database' => __DIR__.'/test.sqlite',
         'prefix'   => '',
    ];
$capsule = new \Illuminate\Database\Capsule\Manager();
$capsule->addConnection($db);
$capsule->setAsGlobal();
$capsule->bootEloquent();

