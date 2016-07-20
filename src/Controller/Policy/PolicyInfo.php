<?php

namespace PP\Portal\Controller\Policy;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

class PolicyInfo extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        /* @var $policy \PP\Portal\DbModel\Policy */
        $policy = $this->PolicyModule->policyInfo($args['id']);

        if (!$policy) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[3010],
            ]);
        }

        return $this->ViewHelper->successView($response, [
                    'data' => $policy->toArray(),
                ],3030);
    }
}
