<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Verify extends AbstractContainer
{
    /**
     * @param ServerRequestInterface $request
     * @param Response               $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $v = new \Valitron\Validator((array) $request->getParsedBody());
        $v->rule('required', ['ppmid', 'date_of_birth']);
        $v->rule('integer', 'ppmid');
        $v->rule('date', 'date_of_birth');


        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020],
            ]);
        }

        $data = $v->data();

        /* @var $user \PP\Portal\DbModel\User */
        $user = $this->UserModule->verifyUser($data);
        if (!$user) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2051],
            ]);
        }

        if ($user->isRegister()) {
            return $this->ViewHelper->toJson($response, ['data' => $this->msgCode[2050],
            ]);
        }

        return $this->ViewHelper->toJson($response, ['data' => $this->msgCode[2040],
        ]);
    }
}
