<?php

namespace PP\Portal\Controller\Policy;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PolicyInfo extends AbstractContainer
{
    /**
     * List Policy by client id.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        /* @var $policy \PP\Portal\DbModel\Policy */
        $policy = $this->c['PolicyModule']->policyInfo($args['id']);

        if ( !$policy ) {
            return $this->c['ViewHelper']->toJson($response, ['errors' =>
                $this->c['msgCode'][3010]
            ]);
        }

        return $this->c['ViewHelper']->toJson($response, [
                    'data' => $policy->toArray(),
                ]);
    }
}
