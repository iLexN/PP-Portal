<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class InfoUpdate extends AbstractContainer
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
        /* @var $client \PP\Portal\DbModel\Client */
        $client = $this->c['UserModule']->client;

        $v = new \Valitron\Validator((array) $request->getParsedBody(), $client->getVisible());

        $client->update($v->data());
        return $this->c['ViewHelper']->toJson($response, ['data' => [
            'title' => 'User Info Updated',
        ]]);
    }
}
