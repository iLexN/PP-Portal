<?php

namespace PP\Middleware;

use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Description of HttpBasicAuthMiddleWare.
 *
 * @author user
 */
class DBLog
{
    /**
     * @var \Slim\Container
     */
    protected $c;

    /**
     * @var Capsule
     */
    protected $capsule;

    public function __construct(\Slim\Container $container, Capsule $capsule)
    {
        $this->c = $container;
        $this->capsule = $capsule;
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
        $response = $next($request, $response);
        $query = $this->capsule->getConnection()->getQueryLog();
        $this->c->logger->info('query', $query);

        return $response;
    }
}
