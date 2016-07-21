<?php

namespace PP\Portal\Controller\Policy;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

class PolicyList extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $out = $this->PolicyModule->getPolicyList();

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $out->toArray(),
                ],3020);
    }
}
