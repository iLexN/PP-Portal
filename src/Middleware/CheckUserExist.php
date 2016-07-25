<?php

namespace PP\Portal\Middleware;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

/**
 * check the url part with the id is client or not.
 *
 * @author user
 */
class CheckUserExist extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, $next)
    {
        $route = $request->getAttribute('route');
        $arguments = $route->getArguments();

        if (!$this->UserModule->isUserExistByID($arguments['id'])) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2010],
            ]);
        }

        return $next($request, $response);
    }
}
