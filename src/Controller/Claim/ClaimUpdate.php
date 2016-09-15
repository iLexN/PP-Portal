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
        if (isset($request->getParsedBody()['cheque'])) {
            $this->ClaimModule->getCheque($request->getParsedBody()['cheque']);
        }

        if (!$v->validate() || !$this->ClaimModule->validateExtraClaimInfo($request->getParsedBody()['status'])) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020]]);
        }

        $this->ClaimModule->saveClaim($v->data());
        $this->ClaimModule->saveExtraClaimInfoloop();

        $code = $v->data()['status'] == 'Submit' ? 6020 : 6021;

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $this->msgCode[$code],
                ], $code);
    }
}
