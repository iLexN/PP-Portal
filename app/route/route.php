<?php

$app->post('/signup', 'PP\Portal\Controller\User\Signup');
$app->post('/login', 'PP\Portal\Controller\User\Login');

$app->post('/test/upload', 'PP\Portal\Controller\Test\UploadAction');

//helper for development
$app->get('/helper/router', 'PP\Claims\Controller\Helper\Router')
        ->setName('helperRouter');
