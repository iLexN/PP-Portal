<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class BankAccActionNew extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        //$acc = $this->UserModule->user->userAcc()->first();

        //if (!$acc) {
            $acc = $this->UserBankAccModule->newBlankAcc($args['id']);
        //}

        $v = $this->UserBankAccModule->validBank((array) $request->getParsedBody(), $acc->getFillable());

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1010],
            ]);
        }

        $this->UserBankAccModule->saveData($acc, $v->data());

        return $this->ViewHelper->toJson($response, ['data' => $this->msgCode[3610],
            ]);
    }
}
