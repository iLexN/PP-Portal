<?php

namespace PP\Portal\Controller\General;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

class OfficeInfo extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $item = $this->pool->getItem('OfficeInfo');
        $out = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter(3600 * 24 * 7);
            $out = $this->dataFromDB();
            $this->pool->save($item->set($out));
        }

        return $this->ViewHelper->withStatusCode($response, ['data' => $out], 1540);
    }

    private function dataFromDB()
    {
        /* @var $office \PP\Portal\DbModel\OfficeInfo */
        $office = \PP\Portal\DbModel\OfficeInfo::get();

        return $office->toArray();
    }
}
