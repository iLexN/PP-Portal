<?php

namespace PP\Portal\Controller\Address;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class AddressDelete extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $address = $this->AddressModule->getAddressByUser($args['acid']);

        if (!$address) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $this->AddressModule->delete($address);

        return $this->ViewHelper->toJson($response, ['data' => $this->msgCode[2625]]);
    }
}
