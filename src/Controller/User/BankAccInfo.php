<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class BankAccInfo extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $info = $this->UserBankAccModule->getByUserID();

        if ($info) {
            return $this->ViewHelper->withStatusCode($response, ['data' => $info->toArray(),
            ], 3630);
        }


        return $this->ViewHelper->toJson($response, ['error' => $this->msgCode[3610],
            ]);
    }
}
