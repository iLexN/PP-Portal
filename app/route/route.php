<?php

$app->post('/verify', 'PP\Portal\Controller\User\Verify')
        ->setName('UserVerify');

$app->post('/login', 'PP\Portal\Controller\User\Login')
        ->setName('UserLogin');
$app->post('/forgot-passowrd', 'PP\Portal\Controller\User\ForgotPassword')
        ->setName('UserChangePassword');

$app->get('/user/{id:\d+}', 'PP\Portal\Controller\User\Info')
        ->setName('UserInfo')
        ->add($checkUserExist);
$app->post('/user/{id:\d+}', 'PP\Portal\Controller\User\InfoUpdate')
        ->add($checkUserExist);

$app->post('/user/{id:\d+}/signup', 'PP\Portal\Controller\User\Signup')
        ->setName('UserSignUp')
        ->add($checkUserExist);

$app->get('/user/{id:\d+}/policy', 'PP\Portal\Controller\Policy\PolicyList')
        ->setName('PolicyList')
        ->add($checkUserExist);
$app->post('/user/{id:\d+}/change-passowrd', 'PP\Portal\Controller\User\ChangePassword')
        ->setName('UserChangePassword')
        ->add($checkUserExist);


$app->post('/test/upload', 'PP\Portal\Controller\Test\UploadAction');
//$app->get('/test/token', 'PP\Portal\Controller\Test\Token')
//        ->setName('TestToken')
//        ->add($checkToken);


//helper for development
$app->get('/helper/router', 'PP\Portal\Controller\Helper\Router')
        ->setName('helperRouter');
