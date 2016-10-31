<?php

namespace PP\Portal\Controller\Address;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class AddressUpdate extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $address = $this->getAddress($args);

        if (!$address) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $v = $this->AddressModule->setValidator($request->getParsedBody(), $address);

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020]]);
        }

        $data = $this->inputData($address->toArray(), $v->data());
        $new = new \PP\Portal\DbModel\Address();

        // todo have no update to server yet
        $this->AddressModule->updateDate($data, $new);

        return $this->ViewHelper->withStatusCode($response, ['data' => $new->toArray()],
                $this->getStatusCode($args));
    }

    private function inputData($address, $input)
    {
        $default = [];
        $default['status'] = 'pending';
        $default['old_id'] = $address['id'];
        $data = array_merge($address, $default, $input);

        unset($data['id']);

        return $data;
    }

    private function getStatusCode($args)
    {
        if ($args['mode'] === 'UserPolicy') {
            return 5060;
        }

        return 2620;
    }

    private function getAddress($args)
    {
        if ($args['mode'] === 'UserPolicy') {
            return $this->UserPolicyModule->userPolicy->policy->address()->find((int) $args['acid']);
        }
        //User
        return $this->UserModule->user->address()->find((int) $args['acid']);
    }
}
