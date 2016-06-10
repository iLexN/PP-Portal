<?php

namespace PP\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Description of HttpBasicAuthMiddleWare.
 *
 * @author user
 */
class HttpBasicAuth
{
    /**
     * @var string
     */
    protected $realm;
    /**
     * @var string
     */
    protected $username;
    /**
     * @var string
     */
    protected $password;

    /**
     * @var \Slim\Container
     */
    protected $c;

    public function __construct(\Slim\Container $container, $realm = 'Protected Area')
    {
        $this->c = $container;
        $this->username = $container->get('firewallConfig')['username'];
        $this->password = $container->get('firewallConfig')['password'];
        $this->realm = $realm;
    }

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
        $authUser = $request->getHeaderLine('PHP_AUTH_USER');
        $authPass = $request->getHeaderLine('PHP_AUTH_PW');

        if ($authUser === $this->username && $authPass === $this->password) {
            return $next($request, $response);
        } else {
            return $this->c['ViewHelper']->toJson($response,['errors' => [
                        'status' => 401,
                        'title'  => 'Need Authenticate',
                    ]])->withHeader('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realm))
                    ->withStatus(401);
        }
    }
}
