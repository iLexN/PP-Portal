<?php

namespace PP\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Description of HttpBasicAuthMiddleWare.
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
        if ($request->hasHeader('PP-Portal-Platform')) {
            $platform = $request->getHeaderLine('PP-Portal-Platform');
            $result = $this->checkPlatform($platform);
        } else {
            $result = false;
        }

        if (!$result) {
            return $this->c['ViewHelper']->toJson($response,['errors' => [
                        'status' => 403,
                        'title'  => 'Platform Header Missing',
                    ]])->withStatus(403);
        }

        return $next($request, $response);
    }

    private function checkPlatform($platform)
    {
        $allowPlatform = ['Web', 'iOS', 'Android'];
        if (!in_array($platform, $allowPlatform)) {
            return false;
        }

        $this->c['platform'] = $platform;

        return true;
    }
}
