<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

class Info extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        /* @var $client \PP\Portal\DbModel\User */
        $user = $this->UserModule->user;

        return $this->ViewHelper->successView($response, ['data' => $user->toArray()], 2021 );
    }
}
