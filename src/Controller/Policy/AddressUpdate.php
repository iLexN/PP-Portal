<?php

namespace PP\Portal\Controller\Policy;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class AddressUpdate extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $address = $this->UserPolicyModule->userPolicy->policy->address()->find((int) $args['acid']);

        if (!$address) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $v = $this->AddressModule->setValidator($request->getParsedBody(), $address);

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020]]);
        }

        $data = $this->inputData($address->toArray(), $v->data());
        $new = new \PP\Portal\DbModel\Address();
        $this->AddressModule->saveData($data, $new);

        return $this->ViewHelper->withStatusCode($response, [
                    'data' => $new->toArray(),
                ], 5060);
    }

    private function inputData($address, $input)
    {
        $default = [];
        $default['status'] = 'pending';
        $default['old_id'] = $address['id'];
        $data = array_merge($address, $default, $input);

        return $data;
    }
}
