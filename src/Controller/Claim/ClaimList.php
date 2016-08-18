<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class ClaimList extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {

        $status = $request->getQueryParam('status','All');

        $claims = $this->ClaimModule->getClaimList($this->UserPolicyModule->userPolicy);

        $group = $claims->groupBy('status');

        if ( $group->has($status)) {
            return $this->ViewHelper->withStatusCode($response, [
                    //'data' => $claims->toArray(),
                    'data' => $group->get($status),
                ], 5030);
        }

        return $this->ViewHelper->withStatusCode($response, [
                    //'data' => $claims->toArray(),
                    'data' => $group->all(),
                ], 5030);
    }
}
