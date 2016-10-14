<?php

namespace PP\Portal\Controller\Advisor;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Info extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $item = $this->pool->getItem('Advisor/'.$args['id']);
        $advisor = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            //$item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $item->expiresAfter(3600 * 12);
            $advisor = \PP\Portal\DbModel\Advisor::find($args['id']);
            $this->pool->save($item->set($advisor));
        }
        
        if ($advisor) {
            $out = ['data' => $advisor->toArray()];

            return $this->ViewHelper->withStatusCode($response, $out, 5040);
        }

        return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[3510],
            ]);
    }
}
