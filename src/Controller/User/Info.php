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
        /* @var $client \PP\Portal\DbModel\User */
        $user = $this->c['UserModule']->user;

        //$out = ['data' => $client->as_array()];
        $out = ['data' => $user->toArray()];

        return $this->c['ViewHelper']->toJson($response, $out);
    }
}
