<?php

namespace PP\Portal\Middleware;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Description of HttpBasicAuthMiddleWare.
 *
 * @author user
 */
class HttpBasicAuth extends AbstractContainer
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

    public function __construct(\Slim\Container $container  )
    {
        parent::__construct($container);
        $this->username = $container->get('firewallConfig')['username'];
        $this->password = $container->get('firewallConfig')['password'];
        $this->realm = 'Protected Area';
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
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[4020],
                    ])->withHeader('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realm))
                    ->withStatus(401);
        }
    }
}
