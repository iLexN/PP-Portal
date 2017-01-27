<?php

namespace PP\Portal\Controller\Holder;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\HolderInfo;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Info extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $holderInfo = $this->getHolderInfo($args['id']);

        if (!$holderInfo) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        return $this->ViewHelper->withStatusCode($response, ['data' => $holderInfo->toArray()],
                2640);
    }

    private function getHolderInfo($id)
    {
        $item = $this->pool->getItem('Holder/'.$id);
        $info = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $info = HolderInfo::with('renew')->find($id);
            $this->pool->save($item->set($info));
        }

        return $info;
    }
}
