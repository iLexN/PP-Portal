<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

class ClaimList extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        
        $userPolicy = $this->UserPolicyModule->getClaimList($args['id']);

        if ( !$userPolicy ) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[5020],
            ]);
        }

        $claims = $this->ClaimModule->getClaimList($userPolicy);

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $claims->toArray()
                ],5030);
    }
}
