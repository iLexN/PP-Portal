<?php

namespace PP\Portal\Controller\General;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OfficeInfo extends AbstractContainer
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
        
        $item = $this->c['pool']->getItem('OfficeInfo');
        $out = $user = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            $item->expiresAfter(3600*24);
            $out = $this->dataFromDB();
            $this->c['pool']->save($item->set($out));
        }

        return $this->c['ViewHelper']->toJson($response, ['data'=>$out]);
    }

    private function dataFromDB(){
        /* @var $office \PP\Portal\DbModel\OfficeInfo */
        $office = \PP\Portal\DbModel\OfficeInfo::get();
        return $office->toArray();
    }
}