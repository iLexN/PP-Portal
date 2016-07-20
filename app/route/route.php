<?php

//General
$app->get('/office-info', 'PP\Portal\Controller\General\OfficeInfo')
        ->setName('OfficeInfo');

$app->post('/verify', 'PP\Portal\Controller\User\Verify')
        ->setName('UserVerify');
$app->get('/check-username/{user_name}', 'PP\Portal\Controller\User\CheckUserName')
        ->setName('CheckUserName');

$app->post('/login', 'PP\Portal\Controller\User\Login')
        ->setName('UserLogin');
$app->post('/forgot-passowrd', 'PP\Portal\Controller\User\ForgotPassword')
        ->setName('UserChangePassword');

//User
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

//Policy
$app->get('/policy/{id:\d+}', 'PP\Portal\Controller\Policy\PolicyInfo')
        ->setName('Policy');
$app->get('/advisor/{id:\d+}', 'PP\Portal\Controller\Advisor\Info')
        ->setName('AdvisorInfo');

//Claims
$app->post('/claim', 'PP\Portal\Controller\Claim\ClaimCreate')
        ->setName('ClaimCreate');

$app->post('/test/upload', 'PP\Portal\Controller\Test\UploadAction');
//$app->get('/test/token', 'PP\Portal\Controller\Test\Token')
//        ->setName('TestToken')
//        ->add($checkToken);


//helper for development
$app->get('/helper/router', 'PP\Portal\Controller\Helper\Router')
        ->setName('helperRouter');
$app->get('/helper/code', 'PP\Portal\Controller\Helper\Code')
        ->setName('helperCode');
