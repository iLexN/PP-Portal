<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class AddressList extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $address = $this->UserModule->user->address()->UserAddress()->get();

        return $this->ViewHelper->withStatusCode($response, ['data' => $address->toArray()], 2600);
    }
}
