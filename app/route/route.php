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
        ->setName('ForgotPassword');
$app->post('/forgot-username', 'PP\Portal\Controller\User\ForgotUsername')
        ->setName('ForgotUsername');

//User
$app->get('/user/{id:\d+}', 'PP\Portal\Controller\User\Info')
        ->setName('UserInfo')
        ->add($checkUserExist);
$app->post('/user/{id:\d+}', 'PP\Portal\Controller\User\InfoUpdate')
        ->setName('UserInfo.Post')
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
$app->get('/user/{id:\d+}/bank-account', 'PP\Portal\Controller\User\BankAccInfo')
        ->setName('BankAccInfo')
        ->add($checkUserExist);
$app->post('/user/{id:\d+}/bank-account', 'PP\Portal\Controller\User\BankAccAction')
        ->setName('BankAccAction')
        ->add($checkUserExist);

//Policy
$app->get('/policy/{id:\d+}', 'PP\Portal\Controller\Policy\PolicyInfo')
        ->setName('Policy');
$app->get('/advisor/{id:\d+}', 'PP\Portal\Controller\Advisor\Info')
        ->setName('AdvisorInfo');
//UserPolicy
$app->get('/user-policy/{id:\d+}/claim', 'PP\Portal\Controller\Claim\ClaimList')
        ->setName('ClaimList')
        ->add($checkUsePolicyrExist);
$app->post('/user-policy/{id:\d+}/claim', 'PP\Portal\Controller\Claim\ClaimCreate')
        ->setName('ClaimCreate')
        ->add($checkUsePolicyrExist);
//Claim
$app->get('/claim/{id:\d+}', 'PP\Portal\Controller\Claim\ClaimInfo')
        ->setName('ClaimInfo')
        ->add($checkClaimExist);
$app->post('/claim/{id:\d+}', 'PP\Portal\Controller\Claim\ClaimUpdate')
        ->setName('ClaimUpdate')
        ->add($checkClaimExist);
$app->post('/claim/{id:\d+}/attachment', 'PP\Portal\Controller\Claim\ClaimAttachment')
        ->setName('ClaimAttachment')
        ->add($checkClaimExist);
$app->get('/attachment/{id:\d+}', 'PP\Portal\Controller\Claim\AttachmentDownload')
        ->setName('AttachmentDownload');
$app->post('/attachment/{id:\d+}', 'PP\Portal\Controller\Claim\AttachmentDel')
        ->setName('AttachmentDel');


//$app->get('/test/token', 'PP\Portal\Controller\Test\Token')
//        ->setName('TestToken')
//        ->add($checkToken);


//helper for development
$app->get('/helper/router', 'PP\Portal\Controller\Helper\Router')
        ->setName('helperRouter');
$app->get('/helper/code', 'PP\Portal\Controller\Helper\Code')
        ->setName('helperCode');
