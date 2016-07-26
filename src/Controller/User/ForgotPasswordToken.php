<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class ForgotPasswordToken extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        if( $this->UserModule->isUserExistByForgotToken($args['token']) ){
            return $this->ViewHelper->withStatusCode($response, ['data' => $this->UserModule->user->toArray()], 2560);
        }

        return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2010],
            ]);

        
    }
}
