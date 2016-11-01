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
        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $this->getResult($args),
                        ], 2630);
    }

    private function getResult($args){

        $item = $this->pool->getItem('User/'.$this->UserModule->user->ppmid.'/people');
        $people = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $people = $this->getPeople($this->getPolicyList($args['id']), $args['id']);
            $this->pool->save($item->set($people));
        }

        return $people;
    }

    private function getPolicyList($id)
    {
        $policylist = UserPolicy::with('policy')
                ->where('ppmid', $id)
                ->where('relationship', 'PolicyHolder')
                ->get();
        $ar = $policylist->filter(function (UserPolicy $item) {
            $policy = $item->policy->status;
            if ($policy === 'Active') {
                return true;
            }

            return false;
        })->map(function (UserPolicy $item) {
            return $item->policy_id;
        });

        return $ar;
    }

    private function getPeople($ar, $id)
    {
        $policyPeople = $this->getPeopleListFromUserPolicy($ar, $id);

        $peopleList = $this->filterPeople($policyPeople,$id);

        return $peopleList->values();
    }

    private function filterPeople($policyPeople,$id)
    {
        $peopleList = $policyPeople->filter(function (UserPolicy $item) use ($id) {
            if ($item->ppmid == $id) {
                return true;
            }
            if ($item->user->profile_permission !== null) {
                return true;
            }

            return false;
        })->map(function (UserPolicy $item) {
            return $item->user->userName();
        });

        return $peopleList;
    }

    private function getPeopleListFromUserPolicy($ar, $id)
    {
        if ($ar->count() === 0) {
            $policyPeople = UserPolicy::with('user')
                            ->where('ppmid', $id)
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
