<?php

namespace PP\Portal\Controller\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Login
{
    /**
     * @var \Slim\Container
     */
    protected $c;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

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
        $data = (array) $request->getParsedBody();

        if ($this->checkRequiredData($data)) {
            return $this->c['ViewHelper']->toJson($response,['errors' => [
                'title' => 'Missing field(s)',
            ]]);
        }

        if ($this->isUserExist($data)) {
            return $this->c['ViewHelper']->toJson($response,$this->success());
        }

        return $this->c['ViewHelper']->toJson($response,['errors' => [
            'title' => 'Login User Not Found',
        ]]);
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    private function checkRequiredData($data)
    {
        return !isset($data['clientID']) || !isset($data['password']);
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
