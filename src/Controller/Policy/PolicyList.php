<?php

namespace PP\Portal\Controller\Policy;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class PolicyList extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $out = $this->UserPolicyModule->getPolicyList();

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $out->toArray(),
                ], 3020);
    }
}
