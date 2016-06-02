<?php
namespace PP\Middleware;

/**
 * Description of HttpBasicAuthMiddleWare
 *
 * @author user
 */
class CheckPlatform
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
        $this->c->logger->info('Middleware CheckPlatform');

        $result = false;
        if ($request->hasHeader('PP-Portal-Platform')) {
            $platform = $request->getHeaderLine('PP-Portal-Platform');
            $result =  $this->logRoute($platform);
        } else {
            $result = false;
        }

        if ( !$result ) {
            return $this->c['view']->render($request, $response, ['errors'=>[
                        'status'=>403,
                        'title'=>'Platform Header Missing'
                    ]])->withStatus(403);
        }

        return $next($request, $response);
    }

    private function logRoute($platform)
    {
        $allowPlatform = ['Web','iOS', 'Android'];
        if ( !in_array($platform, $allowPlatform)) {
            return false;
        }

        $this->c['platform'] = $platform;

        return true;
    }
}
