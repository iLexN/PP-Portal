<?php

namespace PP\Portal\Controller\Holder;

use PP\Portal\AbstractClass\AbstractContainer;
use PP\Portal\DbModel\HolderInfo;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class RenewInfo extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $holderInfo = HolderInfo::find($args['id']);

        if (!$holderInfo) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $renew = $holderInfo->reNewInfo()->where('status', 'Pending')->orderBy('created_at', 'desc')->first();

        return $this->ViewHelper->withStatusCode($response, ['data' => $renew->toArray()],
                2642);
    }
}
