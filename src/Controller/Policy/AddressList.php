<?php

namespace PP\Portal\Controller\Policy;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class AddressList extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $address = $this->getAddress();

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $address->toArray(),
                ], 5050);
    }

    private function getAddress()
    {
        $item = $this->pool->getItem('PolicyAddress/'.$this->UserPolicyModule->userPolicy->id);
        $address = $item->get();

        if ($item->isMiss()) {
            $item->lock();
            //$item->expiresAfter($this->c->get('dataCacheConfig')['expiresAfter']);
            $item->expiresAfter(3600 * 12);
            $address = $this->UserPolicyModule->userPolicy->policy->address()->PolicyAddress()->get();
            $this->pool->save($item->set($address));
        }

        return $address;
    }
}
