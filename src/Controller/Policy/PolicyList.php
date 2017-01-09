<?php

namespace PP\Portal\Controller\Policy;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\Policy;
use PP\Portal\DbModel\User;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class PolicyList extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $collection = $this->UserPolicyModule->getPolicyList();

        $out = $this->formatArray($collection);

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $out->toArray(),
                ], 3020);
    }

    public function formatArray($collection)
    {
        return $collection->map(function (Policy $item) {
            $ar = $item->toArray();
            $people = $item->policyuser->map(function (User $item) {
                $user = $item->userName();
                $user['premium_paid'] = $item->pivot->premium_paid;

                return $user;
            });
            $ar['policyuser'] = $people;

            return $ar;
        });
    }
}
