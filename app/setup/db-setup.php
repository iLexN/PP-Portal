<?php

$dbSetting = $settings['dbConfig'];

//ORM::configure('mysql:host='.$dbSetting['host'].';dbname='.$dbSetting['database']);
//ORM::configure('username', $dbSetting['user']);
//ORM::configure('password', $dbSetting['password']);
//ORM::configure('driver_options', [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
//ORM::configure('return_result_sets', true);
//ORM::configure('logging', $dbSetting['logging']);
//ORM::configure('logger', function ($log_string, $query_time) use ($container) {
//    $container->logger->info($log_string.' in '.$query_time);
//});
//
//ORM::configure('caching', $dbSetting['caching']);
//ORM::configure('caching_auto_clear', true); //automatically clear it on save

//$dbCache = new \Ilex\Cache\IdiormCache($dbSetting['path']);
//ORM::configure('cache_query_result', [$dbCache, 'save']);
//ORM::configure('check_query_cache', [$dbCache, 'isHit']);
//ORM::configure('clear_cache', [$dbCache, 'clear']);
//ORM::configure('create_cache_key', [$dbCache, 'genKey']);

$db = [
        'driver' => 'mysql',
        'host' => $dbSetting['host'],
        'database' => $dbSetting['database'],
        'username' => $dbSetting['user'],
        'password' => $dbSetting['password'],
        'charset'   => 'utf8',
        'collation' => 'utf8_general_ci',
        'prefix'    => '',
    ];
$capsule = new \Illuminate\Database\Capsule\Manager();
$capsule->addConnection($db);
$capsule->setAsGlobal();
$capsule->bootEloquent();

if ($dbSetting['logging'] ){
    $capsule->getConnection()->enableQueryLog();
}
