<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

class ClaimCreate extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        /* @var $claim \PP\Portal\DbModel\Claim */
        $claim = $this->ClaimModule->newClaim($args['id']);

        $v = new \Valitron\Validator((array) $request->getParsedBody(), $claim->getFillable());
        $v->rule('dateFormat', ['date_of_treatment'], 'Y-m-d');

        if ( isset($request->getParsedBody()['bank'])){
            $backAcc = $this->ClaimModule->newBankAcc();
            $vb = new \Valitron\Validator((array) $request->getParsedBody()['bank'], $backAcc->getFillable());
            $vb->rule('required', ['iban', 'bank_swift_code']);
            if (!$vb->validate()) {
                return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1010],
                ]);
            }
        }

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020],
                    ]);
        }

        //var_dump($claim->getVisible());

        $this->ClaimModule->saveClaim($v->data());

        if ( isset($request->getParsedBody()['bank'])){
            $data = $vb->data();
            $data['claim_id'] = $claim->claim_id;
            $this->ClaimModule->saveBank($data);
        }
        
        return $this->ViewHelper->withStatusCode($response, [
                    'data' => ['id'=>$claim->claim_id]
                ],5010);
    }
}
