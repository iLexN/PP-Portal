<?php

$app->post('/signup', 'PP\Portal\Controller\User\Signup')
        ->setName('UserSignUp');
$app->post('/login', 'PP\Portal\Controller\User\Login')
        ->setName('UserLogin');

$app->post('/test/upload', 'PP\Portal\Controller\Test\UploadAction');
$app->get('/test/token', 'PP\Portal\Controller\Test\Token')
        ->setName('TestToken')
        ->add($checkToken);


//helper for development
$app->get('/helper/router', 'PP\Portal\Controller\Helper\Router')
        ->setName('helperRouter');
