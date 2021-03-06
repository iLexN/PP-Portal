<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class RenewInfo extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $user = $this->UserModule->getInfoReNew();

        if ($user) {
            return $this->ViewHelper->withStatusCode($response, ['data' => $user->toArray()], 2022);
        }

        return $this->ViewHelper->withStatusCode(
            $response,
            ['data' => []],
            2023
        );
    }
}
