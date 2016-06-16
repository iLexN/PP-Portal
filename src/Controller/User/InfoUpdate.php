<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class InfoUpdate extends AbstractContainer
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
        $data = (array) $request->getParsedBody();

        /* @var $client \PP\Portal\dbModel\Client */
        $client = $this->c['UserModule']->client;

        $client->update($data);
        //$client->save();

        return $this->c['ViewHelper']->toJson($response, ['data' => [
            'title' => 'User Info Updated',
            ]]);
    }
}
