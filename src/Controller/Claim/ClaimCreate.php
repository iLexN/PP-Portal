<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

class ClaimCreate extends AbstractContainer
{
    /**
     * @var \Valitron\Validator
     */
    private $v;

    public function __invoke(Request $request, Response $response, array $args)
    {
        /* @var $claim \PP\Portal\DbModel\Claim */
        $claim = $this->ClaimModule->newClaim($args['id']);
        $this->v = $this->ClaimModule->validClaim((array) $request->getParsedBody(), $claim->getFillable());

        $this->ClaimModule->parseExtraData($request->getParsedBody());

        //$status = $request->getParsedBody()['status'];
        $status = $request->getParsedBodyParam('status');
        if ($this->validate($status)) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020]]);
        }

        $this->ClaimModule->saveAllInfo($this->v->data());

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => ['id' => $claim->claim_id],
                ], $this->getStatusCode($status));
    }

    private function validate($status)
    {
        return !$this->v->validate() || !$this->ClaimModule->validateClaimInfo($status);
    }

    private function getStatusCode($status)
    {
        return $status == 'Submit' ? 5010 : 5011;
    }
}
