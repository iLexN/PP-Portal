<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class ForgotPasswordTokenUpdate extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $v = new \Valitron\Validator((array) $request->getParsedBody());
        $v->rule('required', ['new_password']);

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1010]]);
        }
        $data = $v->data();

        if (!$this->PasswordModule->isStrongPassword($data['new_password'])) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2510]]);
        }

        if ($this->UserModule->isUserExistByForgotToken($args['token'])) {
            $this->UserModule->savePassword($data['new_password']);

            return $this->ViewHelper->withStatusCode($response,
                    ['data' => $this->UserModule->user->toArray()],
                    2570);
        }

        return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2010]]);
    }
}
