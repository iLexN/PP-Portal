<?php

$autoloader = require dirname(__DIR__) . '/vendor/autoload.php';

$autoloader->addPsr4('PP\Portal\\', __DIR__.'/../src');

$db = [
         'driver'   => 'sqlite',
         'database' => __DIR__.'/test.sqlite',
         'prefix'   => '',
    ];
$capsule = new \Illuminate\Database\Capsule\Manager();
$capsule->addConnection($db);
$capsule->setAsGlobal();
$capsule->bootEloquent();

