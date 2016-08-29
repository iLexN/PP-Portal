<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class ClaimCreate extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        /* @var $claim \PP\Portal\DbModel\Claim */
        $claim = $this->ClaimModule->newClaim($args['id']);
        $v = $this->ClaimModule->validClaim((array) $request->getParsedBody(), $claim->getFillable());

        if (isset($request->getParsedBody()['bank'])) {
            $this->ClaimModule->newBankAcc($request->getParsedBody()['bank']);
        }

        if (!$v->validate() || !$this->ClaimModule->validateExtraClaimInfo($request->getParsedBody()['status'])) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020]]);
        }

        $this->ClaimModule->saveClaim($v->data());
        $this->ClaimModule->saveExtraClaimInfoloop();

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => ['id' => $claim->claim_id],
                ], 5010);
    }
}
