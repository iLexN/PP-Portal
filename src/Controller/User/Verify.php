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
     * @return Response
     */
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $v = $this->validator($request->getParsedBody());

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020]]);
        }

        $data = $v->data();

        /* @var $user \PP\Portal\DbModel\User */
        $this->UserModule->isUserExistByID($data['ppmid']);
        $user = $this->UserModule->user;
        if (!$user) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2010]]);
        }

        //check dob
        if ($user->date_of_birth != $data['date_of_birth']) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2051]]);
        }

        if ($user->isRegister()) {
            return $this->ViewHelper->toJson($response, ['data' => $this->msgCode[2050]]);
        }

        return $this->ViewHelper->toJson($response, ['data' => $this->msgCode[2040]]);
    }

    private function validator($data)
    {
        $v = new \Valitron\Validator($data);
        $v->rule('required', ['ppmid', 'date_of_birth']);
        $v->rule('integer', 'ppmid');
        $v->rule('date', 'date_of_birth');

        return $v;
    }
}
