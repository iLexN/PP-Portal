<?php

namespace PP\Portal\Controller\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class InfoUpdate
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
        $id = $args['id'];
        $data = (array) $request->getParsedBody();

        if ($this->c['UserModule']->isUserExistByID($id)) {
            /* @var $client \PP\Portal\dbModel\Client */
            $client = $this->c['UserModule']->client;

            $client->update($data);
            //$client->save();

            return $this->c['ViewHelper']->toJson($response,['data' => [
                'title' => 'User Info Updated',
                ]]);
        }

        return $this->c['ViewHelper']->toJson($response,['errors' => [
            'title' => 'User Info Not Found',
        ]]);
    }
}
