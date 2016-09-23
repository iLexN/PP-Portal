<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class UserPreferenceUpdate extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $info = $this->UserPreferenceModule->getByUserID();

        if (!$info) {
            throw new \Slim\Exception\NotFoundException($request, $response);
        }

        $v = new \Valitron\Validator((array) $request->getParsedBody(), $info->getFillable());
        $v->rule('required', ['currency', 'currency_receive']);

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020]]);
        }

        $info->update($v->data());
        $this->UserPreferenceModule->clearCache();

        return $this->ViewHelper->withStatusCode($response, ['data' => $info->toArray(),
            ], 3650);
    }
}
