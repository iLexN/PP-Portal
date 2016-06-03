<?php

namespace PP\Portal\Controller\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Info
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

        if ($this->isUserExist($id)) {
            /* @var $client \PP\Portal\dbModel\Client */
            $client = $this->c['UserModule']->client;

            $out = ['data' => $client->as_array()];

            $this->c->logger->info('here output');

            return $this->c['view']->render($request, $response, $out);
        }

        return $this->c['view']->render($request, $response, ['errors' => [
            'title' => 'User Info Not Found',
        ]]);
    }

    /**
     * check email in db or not(already user?).
     *
     * @param int $id
     *
     * @return bool
     */
    private function isUserExist($id)
    {
        return $this->c['UserModule']->isUserExistByID($id);
    }
}
