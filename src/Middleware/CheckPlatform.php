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
class CheckPlatform extends AbstractContainer
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
        if ($request->hasHeader('PP-Portal-Platform')) {
            $platform = $request->getHeaderLine('PP-Portal-Platform');
            $result = $this->checkPlatformValue($platform);
        } else {
            $result = false;
        }

        if (!$result) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => [
                        'status' => 403,
                        'title'  => 'Platform Header Missing',
                    ]])->withStatus(403);
        }

        return $next($request, $response);
    }

    private function checkPlatformValue($platform)
    {
        $allowPlatform = ['Web', 'iOS', 'Android'];
        if (!in_array($platform, $allowPlatform)) {
            return false;
        }

        $this->c['platform'] = $platform;

        return true;
    }
}
