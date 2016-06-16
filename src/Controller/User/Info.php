<?php

namespace PP\Portal\Controller\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PP\Portal\AbstractClass\AbstractContainer;

class Info extends AbstractContainer
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
        /* @var $client \PP\Portal\dbModel\Client */
        $client = $this->c['UserModule']->client;

        //$out = ['data' => $client->as_array()];
        $out = ['data' => $client->toArray()];

        return $this->c['ViewHelper']->toJson($response, $out);
    }
}
