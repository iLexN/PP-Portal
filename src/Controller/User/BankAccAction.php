<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class BankAccAction extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $acc = $this->UserModule->user->userAcc()->first();

        if (!$acc) {
            $acc = $this->UserBankAccModule->newBlankAcc($args['id']);
        }

        $v = new \Valitron\Validator((array) $request->getParsedBody(), $acc->getFillable());
        $v->rule('required', ['iban', 'bank_swift_code']);

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1010],
            ]);
        }

        $this->UserBankAccModule->saveData($acc, $v->data());

        return $this->ViewHelper->toJson($response, ['data' => $this->msgCode[3610],
            ]);
    }
}
