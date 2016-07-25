<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Info extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        /* @var $client \PP\Portal\DbModel\User */
        $user = $this->UserModule->user;

        return $this->ViewHelper->withStatusCode($response, ['data' => $user->toArray()], 2021);
    }
}
