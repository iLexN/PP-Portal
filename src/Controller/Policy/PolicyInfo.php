<?php

namespace PP\Portal\Controller\Policy;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class PolicyInfo extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        /* @var $policy \PP\Portal\DbModel\Policy */
        $policy = $this->UserPolicyModule->userPolicy;

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $policy->toArray(),
                ], 3030);
    }
}
