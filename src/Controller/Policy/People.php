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
        $policyPeople = UserPolicy::with('user')
                        ->where('policy_id', $args['id'])->get();

        /* @var $item \PP\Portal\DbModel\UserPolicy */
        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $policyPeople->map(function (UserPolicy $item) {
                        return $item->user->userName();
                    }),
                        ], 3040);
    }
}
