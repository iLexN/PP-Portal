<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\UserPolicy;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class People extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {

        $ar = $this->getPolicyList($args['id']);

        $policyPeople = $this->getPeople($ar, $args['id']);

        /* @var $item \PP\Portal\DbModel\UserPolicy */
        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $policyPeople->map(function (UserPolicy $item) {
                        return $item->user->userName();
                    }),
                        ], 2630);
    }

    private function getPolicyList($id){
        $policylist = UserPolicy::select('policy_id')
                ->where('ppmid', $id)
                ->where('relationship','PolicyHolder')
                ->get();
        $ar = $policylist->map(function($item){
            return $item->policy_id;
        });
        return $ar;
    }

    private function getPeople($ar,$id){
        if ( $ar->count() === 0 ) {
            $policyPeople = UserPolicy::with('user')
                            ->where('ppmid',$id)
                            ->groupBy('ppmid')
                            ->get();
        } else {
            $policyPeople = UserPolicy::with('user')
                            ->where('policy_id', $ar)
                            ->groupBy('ppmid')
                            ->get();
        }
        return $policyPeople;
    }
}
