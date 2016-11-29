<?php

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Ftp as Adapter;

$autoloader = require '../vendor/autoload.php';
$autoloader->addPsr4('Ilex\Deploy\\', __DIR__.'/../deploy');



$deploy = new Ilex\Deploy\DeployGit();

$f = $deploy->diffFiles();

print_r($f);


$filesystem = new Filesystem(new Adapter([
    'host' => '10.0.2.49',
    'username' => 'dev',
    'password' => 'devP@',
    /** optional config settings */
    'port' => 21,
    'root' => '/home/dev/www/portal',
    'passive' => true,
    //'ssl' => true,
    'timeout' => 30,
]));

foreach ( $f as $fileArray ) {
    $filesystem->write($fileArray[1], file_get_contents('../'.$fileArray[1]));
}