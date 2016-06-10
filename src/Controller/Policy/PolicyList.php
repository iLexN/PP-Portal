<?php

namespace PP\Portal\Controller\Policy;

use League\Fractal\Resource\Collection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PolicyList
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
        if ($this->c['UserModule']->isUserExistByID($args['id'])) {
            /* @var $client \PP\Portal\dbModel\Client */
            $client = $this->c['UserModule']->client;

            /* @var $policyList \PP\Portal\dbModel\Policies */
            $policyList = $client->policies()
                    //->order_by_desc('Policy_ID')
                    ->orderBy('Policy_ID','desc')
                    ->get();
                    //->find_many();

            return $this->c['ViewHelper']->toJson($response,[
                        'data' => $policyList->toArray()
                    ]);
        }

        return $this->c['ViewHelper']->toJson($response,['errors' => [
            'title' => 'User Info Not Found',
        ]]);
    }
}
