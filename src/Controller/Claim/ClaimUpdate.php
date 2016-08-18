<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class ClaimUpdate extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $v = $this->ClaimModule->validClaim((array) $request->getParsedBody(), $this->ClaimModule->claim->getFillable());

        if (isset($request->getParsedBody()['bank'])) {
            $this->ClaimModule->getBankAcc($request->getParsedBody()['bank']);
        }

        if (!$v->validate() || !$this->ClaimModule->validateExtraClaimInfo($request->getParsedBody()['status'])) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020],
                    ]);
        }

        $this->ClaimModule->saveClaim($v->data());
        $this->ClaimModule->saveExtraClaimInfoloop();

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $this->msgCode[6020],
                ], 6020);
    }
}