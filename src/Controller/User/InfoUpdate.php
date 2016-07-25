<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class InfoUpdate extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {

        /* @var $newInfo \PP\Portal\DbModel\UserInfoReNew */
        $newInfo = $this->UserModule->user->reNewInfo()->where('status', 'Pending')->first();

        if (!$newInfo) {
            $newInfo = $this->UserModule->newInfoReNew();
        }

        $v = new \Valitron\Validator((array) $request->getParsedBody(), $newInfo->getVisible());
        $v->rule('dateFormat', ['date_of_birth'], 'Y-m-d');

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020],
                    ]);
        }

        $this->UserModule->saveInfoReNew($newInfo, $v->data());

        return $this->ViewHelper->toJson($response, ['data' => $this->msgCode[2020],
        ]);
    }
}
