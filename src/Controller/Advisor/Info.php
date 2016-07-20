<?php

namespace PP\Portal\Controller\Advisor;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

class Info extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {

        //todo add cache
        $advisor = \PP\Portal\DbModel\Advisor::find($args['id']);

        if ( $advisor ) {
            $out = ['data' => $advisor->toArray()];

            return $this->ViewHelper->successView($response, $out,5040 );
        }

        return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[3510],
            ]);
    }
}
