<?php

$app->post('/signup', 'PP\Portal\Controller\User\Signup');
$app->post('/login', 'PP\Portal\Controller\User\Login');

$app->post('/test/upload', 'PP\Portal\Controller\Test\UploadAction');
$app->get('/test/token', 'PP\Portal\Controller\Test\Token')
        ->add($checkToken);


//helper for development
$app->get('/helper/router', 'PP\Portal\Controller\Helper\Router')
        ->setName('helperRouter');
