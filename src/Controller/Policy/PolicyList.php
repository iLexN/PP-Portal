<?php

namespace PP\Portal\Controller\Policy;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PP\Portal\AbstractClass\AbstractContainer;

class PolicyList extends AbstractContainer
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

        /* @var $policyList \PP\Portal\dbModel\Policies */
        $policyList = $client->policies()
                ->orderBy('Policy_ID', 'desc')
                ->get();

        return $this->c['ViewHelper']->toJson($response, [
                    'data' => $policyList->toArray(),
                ]);
    }
}
