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
class CheckUsePolicyrExist extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, $next)
    {
        $route = $request->getAttribute('route');
        $arguments = $route->getArguments();

        if (!$this->UserPolicyModule->getUerPolicy($arguments['id'])) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[5020],
            ]);
        }

        return $next($request, $response);
    }
}
