<?php
namespace PP\Middleware;

/**
 * Description of HttpBasicAuthMiddleWare
 *
 * @author user
 */
class RouteLog
{
    
    /**
     * @var \Slim\Container
     */
    protected $c;
    
    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

    /**
     * logRoute app setting determineRouteBeforeAppMiddleware = true
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $this->c->logger->info('Middleware RouteLog');

        $platform = $this->c['platform'];
        $route = $request->getAttribute('route');

        /* @var $routelog \PP\Portal\dbModel\RouteLog */
        $routelog = \PP\Portal\dbModel\RouteLog::create();
        $routelog->date = date("Y-m-d");
        $routelog->platform = $platform;
        $routelog->name = json_encode($route->getName());
        $routelog->groups = json_encode($route->getGroups());
        $routelog->methods = json_encode($route->getMethods());
        $routelog->arguments = json_encode($route->getArguments());
        $routelog->save();

        return $next($request, $response);
    }
}
