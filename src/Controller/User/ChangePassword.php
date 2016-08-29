<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class ChangePassword extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $v = new \Valitron\Validator((array) $request->getParsedBody());
        $v->rule('required', ['old_password', 'new_password']);

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1010]]);
        }

        $data = $v->data();

        if (!$this->PasswordModule->isStrongPassword($data['new_password'])) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2510]]);
        }

        if (!$this->UserModule->user->passwordVerify($data['old_password'])) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2520]]);
        }

        $this->UserModule->savePassword($this->PasswordModule->passwordHash($data['new_password']));

        return $this->ViewHelper->toJson($response, ['data' => $this->msgCode[2530]]);
    }
}
