<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class ClaimList extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $claims = $this->ClaimModule->getClaimList($this->UserPolicyModule->userPolicy);

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $claims->toArray(),
                ], 5030);
    }
}
