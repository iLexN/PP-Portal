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
        $v->rule('required', ['clientID', 'password']);

        if (!$v->validate()) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => 
                $this->c['msgCode'][1010]
            ]);
        }

        if ($this->isUserExist($v->data())) {
            return $this->c['ViewHelper']->toJson($response, $this->success());
        }

        return $this->c['ViewHelper']->toJson($response, ['errors' => 
            $this->c['msgCode'][2010]
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
        return $this->c['UserModule']->isUserExistByID($data['clientID']) &&
                $this->c['UserModule']->client->verifyPassword($data['password']);
    }

    private function success()
    {
        /* @var $user \PP\Portal\dbModel\Client */
        $client = $this->c['UserModule']->client;

        return ['data' => [
                'id' => $client->Client_NO,
                ]];
    }
}
