<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class BankAccActionUpdate extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $acc = $this->getAcc($args);

        if (!$acc) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $v = $this->UserBankAccModule->validBank((array) $request->getParsedBody(), $acc->getFillable());

        if (!$v->validate()) {
            return $this->ViewHelper->withStatusCode($response, ['errors' => $v->errors()], 1010);
        }

        $data = $v->data();

        if ($this->UserBankAccModule->checkNickName($this->UserModule->user->ppmid, $data['nick_name']) >= $this->checkNickName($args, $data, $acc)) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[3613]]);
        }

        $this->UserBankAccModule->saveData($acc, $data);

        return $this->ViewHelper->withStatusCode($response, ['data' => $acc->toArray()], $this->getCode($args));
    }

    private function checkNickName($args, $data , $acc)
    {
        if ( $args['mode'] === 'create' || $data['nick_name'] != $acc->nick_name ) {
            return 1;
        }

        return 2;
    }

    private function getCode($args)
    {
        $code = $args['mode'] === 'create' ? 3610 : 3611;

        //return $this->msgCode[$code];
        return $code;
    }

    private function getAcc($args)
    {
        if ($args['mode'] === 'create') {
            return $this->UserBankAccModule->newBlankAcc($args['id']);
        }

        return $this->UserModule->user->userAcc()->find($args['acid']);
    }
}
