<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

class ClaimInfo extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $claimInfo = $this->ClaimModule->claim;

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $claimInfo->toArray()
                ],6020);
    }
}
