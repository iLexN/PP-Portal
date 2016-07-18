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
        $claim = $this->c['ClaimModule']->newClaims();

        //var_dump($claim->getFillable());
        
        $v = new \Valitron\Validator((array) $request->getParsedBody(), $claim->getVisible());
        $v->rule('required', ['user_policy_id']);
        $v->rule('dateFormat', ['date_of_treatment'] , 'Y-m-d');
        $v->rule('integer', ['user_policy_id']);

        if(!$v->validate()) {
            return $this->c['ViewHelper']->toJson($response, ['errors' =>
                        $this->c['msgCode'][1020]
                    ]);
        }
        

        return $this->c['ViewHelper']->toJson($response, [
                    'data' => $this->c['msgCode'][5010],
                ]);
    }
}
