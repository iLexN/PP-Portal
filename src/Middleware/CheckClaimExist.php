<?php

namespace PP\Portal\Middleware;

use PP\Portal\AbstractClass\AbstractContainer;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

/**
 * check the url part with the id is client or not.
 *
 * @author user
 */
class CheckClaimExist extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, $next)
    {
        $route = $request->getAttribute('route');
        $arguments = $route->getArguments();

        if ( !$this->ClaimModule->geInfoById($arguments['id']) ) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[6010],
            ]);
        }

        return $next($request, $response);
    }
}
