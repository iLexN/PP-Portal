<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class InfoUpdate extends AbstractContainer
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

        /* @var $newInfo \PP\Portal\DbModel\UserInfoReNew */
        $newInfo = $this->c['UserModule']->user->reNewInfo()->where('status','Pending')->first();

        if ( $newInfo ){
            //todo alrady exist
        } else {
            $newInfo = new \PP\Portal\DbModel\UserInfoReNew();
            $newInfo->ppmid = $this->c['UserModule']->user->ppmid;
            $newInfo->status = 'Pending';
        }

        $v = new \Valitron\Validator((array) $request->getParsedBody(), $newInfo->getVisible());
        $v->rule('dateFormat', ['date_of_birth'] , 'Y-m-d');

        if(!$v->validate()) {
            return $this->c['ViewHelper']->toJson($response, ['errors' =>
                        $this->c['msgCode'][1020]
                    ]);
        }

        foreach  ( $v->data() as $k => $v ){
            $newInfo->{$k} = $v;
        }

        $newInfo->save();

        return $this->c['ViewHelper']->toJson($response, ['data' => 
            $this->c['msgCode'][2020]
        ]);
    }
}
