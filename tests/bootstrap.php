<?php

$autoloader = require dirname(__DIR__).'/vendor/autoload.php';

$autoloader->addPsr4('PP\Portal\\', __DIR__.'/../src');

$db = [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'member',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8',
        'collation' => 'utf8_general_ci',
        'prefix'    => '',
    ];
$capsule = new \Illuminate\Database\Capsule\Manager();
$capsule->addConnection($db);
$capsule->setAsGlobal();
$capsule->bootEloquent();
