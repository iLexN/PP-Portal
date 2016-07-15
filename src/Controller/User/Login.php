<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Login extends AbstractContainer
{
    /**
     * Login Post action.
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
        $v->rule('required', ['user_name', 'password']);

        if (!$v->validate()) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => 
                $this->c['msgCode'][1010]
            ]);
        }

        if ($this->isUserExist($v->data())) {
            return $this->c['ViewHelper']->toJson($response, $this->success());
        }

        return $this->c['ViewHelper']->toJson($response, ['errors' => 
            $this->c['msgCode'][2080]
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
        if ( !$this->c['UserModule']->isUserExistByUsername($data['user_name']) ) {
            return false;
        }

        if ( !$this->c['UserModule']->user->passwordVerify($data['password']) ){
            return false;
        }

        return true;
    }

    private function success()
    {
        /* @var $user \PP\Portal\DbModel\User */
        $user = $this->c['UserModule']->user;

        return ['data' => [
                'id' => $user->ppmid,
                ]];
    }
}
