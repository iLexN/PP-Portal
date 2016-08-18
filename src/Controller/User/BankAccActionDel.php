<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class BankAccActionDel extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $acc = $this->UserModule->user->userAcc()->find($args['acid']);

        if ( !$acc ) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $this->UserBankAccModule->delBank($acc);

        return $this->ViewHelper->toJson($response, ['data' => $this->msgCode[3612],
            ]);
    }
}
