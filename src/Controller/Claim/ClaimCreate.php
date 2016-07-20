<?php

namespace PP\Portal\Controller\Claim;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ClaimCreate extends AbstractContainer
{
    /**
     * List Policy by client id.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        /* @var $claim \PP\Portal\DbModel\Claim */
        $claim = $this->ClaimModule->newClaim();

        //var_dump($claim->getFillable());

        $v = new \Valitron\Validator((array) $request->getParsedBody(), $claim->getVisible());
        $v->rule('required', ['user_policy_id']);
        $v->rule('dateFormat', ['date_of_treatment'], 'Y-m-d');
        $v->rule('integer', ['user_policy_id']);

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020],
                    ]);
        }

        $this->ClaimModule->saveClaim($v->data());
        
        return $this->ViewHelper->successView($response, [
                    'data' => ['id'=>$claim->claim_id]
                ],5010);
    }
}
