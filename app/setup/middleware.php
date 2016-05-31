<?php

$app->add(new \PP\Middleware\Firewall($container));

$app->add(new \PP\Middleware\HttpBasicAuth($container));

$checkToken = new \PP\Middleware\CheckToken($container);
