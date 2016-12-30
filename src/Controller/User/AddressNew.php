<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class AddressNew extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $address = new \PP\Portal\DbModel\Address();

        $v = $this->AddressModule->setValidator($request->getParsedBody(), $address);

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020]]);
        }

        $default = [];
        //$default['address_type'] = 'user';
        $default['status'] = 'Active';
        $default['ppmid'] = $this->UserModule->user->ppmid;

        $data = array_merge($default, $v->data());
        $this->AddressModule->save($data, $address);

        return $this->ViewHelper->withStatusCode($response, ['data' => $address->toArray()], 2610);
    }
}
