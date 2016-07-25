<?php

namespace PP\Portal\Middleware;

use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * logs sql.
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

        $route = $request->getAttribute('route');
        $this->c->logger->info('route method', $route->getMethods());
        $this->c->logger->info('route name', [$route->getName()]);

        return $response;
    }
}
