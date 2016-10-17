<?php

namespace PP\Portal\Controller\Policy;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\UserPolicy;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class People extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $policyPeople = $this->findPeople($args['id']);

        /* @var $item \PP\Portal\DbModel\UserPolicy */
        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $policyPeople->map(function (UserPolicy $item) {
                        return $item->user->userName();
                    }),
                        ], 3040);
    }

    private function findPeople($id)
    {
        $item = $this->pool->getItem('People/'.$id);
        $policyPeople = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            //$item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $item->expiresAfter(3600 * 12);
            $policyPeople = UserPolicy::with('user')
                        ->where('policy_id', $id)->get();
            $this->pool->save($item->set($policyPeople));
        }

        return $policyPeople;
    }
}
