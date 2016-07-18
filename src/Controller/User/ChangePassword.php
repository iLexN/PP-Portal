<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ChangePassword extends AbstractContainer
{
    /**
     * change password.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $v = new \Valitron\Validator((array) $request->getParsedBody());
        $v->rule('required', ['old_password', 'new_password']);

        if (!$v->validate()) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => $this->c['msgCode'][1010],
            ]);
        }

        $data = $v->data();

        if (!$this->c['PasswordModule']->isStrongPassword($data['new_password'])) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => $this->c['msgCode'][2510],
            ]);
        }

        /* @var $user \PP\Portal\DbModel\User */
        $user = $this->c['UserModule']->user;

        if (!$user->passwordVerify($data['old_password'])) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => $this->c['msgCode'][2520],
            ]);
        }

        $this->c['UserModule']->savePassword($this->c['PasswordModule']->passwordHash($data['new_password']));

        return $this->c['ViewHelper']->toJson($response, ['data' => $this->c['msgCode'][2530],
            ]);
    }
}
