<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Request;
use Slim\Http\Response;

class ClaimUpdate extends AbstractContainer
{
    /**
     * @var \Valitron\Validator
     */
    private $v;

    public function __invoke(Request $request, Response $response, array $args)
    {
        $mode = $args['mode'];

        $this->setClaim($mode, $args['id']);

        $this->v = $this->ClaimModule->validClaim((array) $request->getParsedBody(), $this->ClaimModule->claim->getFillable());

        $this->ClaimModule->parseExtraData($request->getParsedBody());

        //$status = $request->getParsedBody()['status'];
        $status = $request->getParsedBodyParam('status');
        if ($this->validate($status)) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020]]);
        }

        $this->ClaimModule->saveAllInfo($this->v->data());

        return $this->ViewHelper->withStatusCode($response, [
                    //'data' => $this->msgCode[$this->getStatusCode($status)],
                    'data' => ['id' => $this->ClaimModule->claim->claim_id],
                ], $this->getStatusCode($status,$mode));
    }

    private function validate($status)
    {
        return !$this->v->validate() || !$this->ClaimModule->validateClaimInfo($status);
    }

    private function getStatusCode($status,$mode)
    {
        if ( $mode === 'create') {
            return $status == 'Submit' ? 5010 : 5011;
        }
        return $status == 'Submit' ? 6020 : 6021;
    }

    private function setClaim($mode,$id) {
        if ( $mode === 'create') {
            $this->ClaimModule->newClaim($id);
        }
    }
}
