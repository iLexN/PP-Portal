<?php

$app->post('/signup', 'PP\Portal\Controller\User\Signup')
        ->setName('UserSignUp');

$app->post('/login', 'PP\Portal\Controller\User\Login')
        ->setName('UserLogin');
$app->post('/forgot-passowrd', 'PP\Portal\Controller\User\ForgotPassword')
        ->setName('UserChangePassword');

$app->get('/user/{id}', 'PP\Portal\Controller\User\Info')
        ->setName('UserInfo');
$app->get('/user/{id}/policy', 'PP\Portal\Controller\Policy\PolicyList')
        ->setName('PolicyList');
$app->post('/user/{id}/change-passowrd', 'PP\Portal\Controller\User\ChangePassword')
        ->setName('UserChangePassword');


$app->post('/test/upload', 'PP\Portal\Controller\Test\UploadAction');
//$app->get('/test/token', 'PP\Portal\Controller\Test\Token')
//        ->setName('TestToken')
//        ->add($checkToken);


//helper for development
$app->get('/helper/router', 'PP\Portal\Controller\Helper\Router')
        ->setName('helperRouter');
