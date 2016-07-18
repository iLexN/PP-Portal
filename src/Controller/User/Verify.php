<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Verify extends AbstractContainer
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $v = new \Valitron\Validator((array) $request->getParsedBody());
        $v->rule('required', ['ppmid','date_of_birth']);
        $v->rule('integer','ppmid');
        $v->rule('date','date_of_birth');


        if (!$v->validate()) {
            return $this->c['ViewHelper']->toJson($response, ['errors' =>
                $this->c['msgCode'][1020]
            ]);
        }
        
        $data = $v->data();

        /* @var $user \PP\Portal\DbModel\User */
        $user = $this->c['UserModule']->verifyUser($data);
        if ( !$user ) {
            return $this->c['ViewHelper']->toJson($response, ['errors' =>
                $this->c['msgCode'][2051]
            ]);
        }

        if ( $user->isRegister()) {
            return $this->c['ViewHelper']->toJson($response, ['data' =>
                $this->c['msgCode'][2050]
            ]);
        }
        
        return $this->c['ViewHelper']->toJson($response, ['data' =>
            $this->c['msgCode'][2040]
        ]);
    }
}
