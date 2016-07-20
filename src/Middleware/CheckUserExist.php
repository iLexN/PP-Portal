<?php

namespace PP\Portal\Middleware;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * check the url part with the id is client or not.
 *
 * @author user
 */
class CheckUserExist extends AbstractContainer
{
    /**
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

        if (!$this->UserModule->isUserExistByID($arguments['id'])) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2010],
            ]);
        }

        return $next($request, $response);
    }
}
