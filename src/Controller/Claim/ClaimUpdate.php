<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Response;
use Slim\Http\Request;

class ClaimUpdate extends AbstractContainer
{
    /**
     *
     * @var \Valitron\Validator
     */
    private $v;

    public function __invoke(Request $request, Response $response, array $args)
    {
        $this->v = $this->ClaimModule->validClaim((array) $request->getParsedBody(), $this->ClaimModule->claim->getFillable());

        $this->ClaimModule->parseExtraData($request->getParsedBody());

        //$status = $request->getParsedBody()['status'];
        $status = $request->getParsedBodyParam('status');
        if ($this->validate($status)) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020]]);
        }

        $this->ClaimModule->saveAllInfo($this->v->data());

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $this->msgCode[$this->getStatusCode($status)],
                ], $this->getStatusCode($status));
    }

    private function validate($status){
        return !$this->v->validate() || !$this->ClaimModule->validateClaimInfo($status);
    }

    private function getStatusCode($status){
        return $status == 'Submit' ? 6020 : 6021;
    }

}
