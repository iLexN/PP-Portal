<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Login extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $v = new \Valitron\Validator((array) $request->getParsedBody());
        $v->rule('required', ['user_name', 'password']);

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1010],
            ]);
        }

        $data = $v->data();

        if (!$this->UserModule->isUserExistByUsername($data['user_name'])) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2010]]);
        }

        if (!$this->UserModule->user->passwordVerify($data['password'])) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2080]]);
        }

        $this->checkNeedRehash($v->data());

        return $this->ViewHelper->withStatusCode($response, $this->success(), 2081);

    }

    private function success()
    {
        /* @var $user \PP\Portal\DbModel\User */
        $user = $this->UserModule->user;

        return ['data' => [
                'id' => $user->ppmid,
                ]];
    }

    private function checkNeedRehash($data)
    {
        /* @var $user \PP\Portal\DbModel\User */
        $user = $this->UserModule->user;
        if ($user->needReHash()) {
            $user->password = $this->PasswordModule->passwordHash($data['password']);
            $user->save();
        }
    }
}
