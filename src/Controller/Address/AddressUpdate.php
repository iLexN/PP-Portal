<?php

namespace PP\Portal\Controller\Address;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class AddressUpdate extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $address = $this->AddressModule->getAddressByUser($args['acid']);

        if (!$address) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $v = $this->AddressModule->setValidator($request->getParsedBody(), $address);

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020]]);
        }

        //$data = $this->inputData($address->toArray(), $v->data());
        //$new = new \PP\Portal\DbModel\Address();

        $data = $v->data();

        if ($this->AddressModule->checkNickName($this->UserModule->user->ppmid, $data['nick_name']) >= 1) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2626]]);
        }

        $this->AddressModule->save($data, $address);

        return $this->ViewHelper->withStatusCode($response, ['data' => $address->toArray()],
                $this->getStatusCode());
    }

    /*
    private function inputData($address, $input)
    {
        $default = [];
        $default['status'] = 'Pending';
        $default['old_id'] = $address['id'];
        $data = array_merge($address, $default, $input);

        unset($data['id']);

        return $data;
    }*/

    private function getStatusCode()
    {
        return 2620;
    }
}
