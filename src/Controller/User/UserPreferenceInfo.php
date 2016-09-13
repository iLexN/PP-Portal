<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class UserPreferenceInfo extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $info = $this->UserPreferenceModule->getByUserID();

        if ( $info === null) {
            $info = $this->UserPreferenceModule->newPreference();
        }

        return $this->ViewHelper->withStatusCode($response, ['data' => $info->toArray(),
            ], 3640);

    }
}
