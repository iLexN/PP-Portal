<?php

namespace PP\Portal\Controller\Advisor;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Info extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {

        //todo add cache
        $advisor = \PP\Portal\DbModel\Advisor::find($args['id']);

        if ($advisor) {
            $out = ['data' => $advisor->toArray()];

            return $this->ViewHelper->withStatusCode($response, $out, 5040);
        }

        return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[3510],
            ]);
    }
}
