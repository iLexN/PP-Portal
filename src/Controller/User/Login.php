<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

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

        if ($this->isUserExist($v->data())) {
            return $this->ViewHelper->successView($response, $this->success(), 2081);
        }

        return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2080],
        ]);
    }

    /**
     * check email in db or not(already user?).
     *
     * @param array $data
     *
     * @return bool
     */
    private function isUserExist($data)
    {
        if (!$this->UserModule->isUserExistByUsername($data['user_name'])) {
            return false;
        }

        if (!$this->UserModule->user->passwordVerify($data['password'])) {
            return false;
        }

        return true;
    }

    private function success()
    {
        /* @var $user \PP\Portal\DbModel\User */
        $user = $this->UserModule->user;

        return ['data' => [
                'id' => $user->ppmid,
                ]];
    }
}
