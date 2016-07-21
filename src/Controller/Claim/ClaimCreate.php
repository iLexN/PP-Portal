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

        $v = new \Valitron\Validator((array) $request->getParsedBody(), $claim->getVisible());
        //$v->rule('required', ['user_policy_id']);
        $v->rule('dateFormat', ['date_of_treatment'], 'Y-m-d');

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020],
                    ]);
        }

        $this->ClaimModule->saveClaim($v->data());
        
        return $this->ViewHelper->withStatusCode($response, [
                    'data' => ['id'=>$claim->claim_id]
                ],5010);
    }
}
