<?php

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Ftp as Adapter;

$autoloader = require '../vendor/autoload.php';
$autoloader->addPsr4('Ilex\Deploy\\', __DIR__.'/../deploy');



$deploy = new Ilex\Deploy\DeployGit();
/*
$files = $deploy->goLive('0.1.0');
print_r($files);
exit();
*/
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

foreach ($f as $fileArray) {
    switch ($fileArray[0]) {
        case 'D':
            if ( $filesystem->has($fileArray[2]) ) {
                $filesystem->delete($fileArray[2]);
            }
            break;

        default:
            $filesystem->put($fileArray[1], file_get_contents('../'.$fileArray[1]));
    }
}
