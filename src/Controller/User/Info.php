<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Info extends AbstractContainer
{
    /**
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
