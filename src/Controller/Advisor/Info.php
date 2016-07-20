<?php

namespace PP\Portal\Controller\Advisor;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Info extends AbstractContainer
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

        //todo add cache
        $advisor = \PP\Portal\DbModel\Advisor::find($args['id']);

        if ( $advisor ) {
            $out = ['data' => $advisor->toArray()];

            return $this->ViewHelper->toJson($response, $out);
        }

        return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[3510],
            ]);
    }
}
