<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$autoloader = require '../vendor/autoload.php';
$autoloader->addPsr4('PP\Portal\Controller\\', __DIR__.'/../src/Controller');
$autoloader->addPsr4('PP\Portal\dbModel\\', __DIR__.'/../src/DB-Model');
$autoloader->addPsr4('PP\Module\\', __DIR__.'/../src/Module');
$autoloader->addPsr4('PP\Middleware\\', __DIR__.'/../src/Middleware');