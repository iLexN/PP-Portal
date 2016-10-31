<?php

namespace PP\Portal\Controller\Holder;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use PP\Portal\DbModel\HolderInfo;

class Info extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $holderInfo = HolderInfo::find($args['id']);

        return $this->ViewHelper->withStatusCode($response, ['data' => $holderInfo->toArray()],
                2640);
    }

}
