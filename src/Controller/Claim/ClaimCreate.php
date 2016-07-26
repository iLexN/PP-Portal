<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class ClaimCreate extends AbstractContainer
{
    /**
     * @var array
     */
    private $v = [];

    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        /* @var $claim \PP\Portal\DbModel\Claim */
        $claim = $this->ClaimModule->newClaim($args['id']);

        $v = new \Valitron\Validator((array) $request->getParsedBody(), $claim->getFillable());
        $v->rule('dateFormat', ['date_of_treatment'], 'Y-m-d');

        if (isset($request->getParsedBody()['bank'])) {
            $this->vBank($request->getParsedBody()['bank']);
        }

        if (!$v->validate() || !$this->validateV()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020],
                    ]);
        }

        $this->ClaimModule->saveClaim($v->data());

        $this->processExtraData();

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => ['id' => $claim->claim_id],
                ], 5010);
    }

    private function vBank($data)
    {
        $backAcc = $this->ClaimModule->newBankAcc();
        $vb = new \Valitron\Validator($data, $backAcc->getFillable());
        $vb->rule('required', ['iban', 'bank_swift_code']);
        $this->v['bank'] = $vb;
    }

    private function validateV()
    {
        foreach ($this->v as $v) {
            if (!$v->validate()) {
                return false;
            }
        }

        return true;
    }

    private function processExtraData()
    {
        foreach ($this->v as $k => $v) {
            $this->saveExtraData($k, $v);
        }
    }

    private function saveExtraData($k, $v)
    {
        switch ($k) {
            case 'bank':
                $data = $v->data();
                $this->ClaimModule->saveBankToUserAccout($data);
                $data['claim_id'] = $this->ClaimModule->claim->claim_id;
                $this->ClaimModule->saveBank($data);
                
                break;

            default:
                break;
        }
    }
}
