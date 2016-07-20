<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CheckUserName extends AbstractContainer
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        if ($this->UserModule->isUserNameExist($args['user_name'])) {
            return $this->ViewHelper->toJson($response, ['data' => $this->msgCode[2060],
            ]);
        }

        return $this->ViewHelper->toJson($response, ['data' => $this->msgCode[2070],
            ]);
    }
}
