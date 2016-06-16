<?php

namespace PP\Portal\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PP\Portal\AbstractClass\AbstractContainer;

/**
 * Description of HttpBasicAuthMiddleWare.
 *
 * @author user
 */
class CheckUserExist extends AbstractContainer
{
    /**
     * logRoute app setting determineRouteBeforeAppMiddleware = true.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $route = $request->getAttribute('route');
        $arguments = $route->getArguments();

        if (!$this->c['UserModule']->isUserExistByID($arguments['id'])) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => [
                'title' => 'User Not Found',
            ]]);
        }

        return $next($request, $response);
    }
}
