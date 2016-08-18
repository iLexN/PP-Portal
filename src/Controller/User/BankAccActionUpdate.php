<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class BankAccActionUpdate extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $acc = $this->UserModule->user->userAcc()->find($args['acid']);

        if (!$acc) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $v = $this->UserBankAccModule->validBank((array) $request->getParsedBody(), $acc->getFillable());

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1010],
            ]);
        }

        $this->UserBankAccModule->saveData($acc, $v->data());

        return $this->ViewHelper->toJson($response, ['data' => $this->msgCode[3611],
            ]);
    }
}
