<?php

namespace PP\Portal\Middleware;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

/**
 * CheckPlatform header PP-Portal-Platform.
 */
class CheckPlatform extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, $next)
    {
        $platform = false;

        if ($request->hasHeader('PP-Portal-Platform')) {
            $platform = $request->getHeaderLine('PP-Portal-Platform');
            $result = $this->checkPlatformValue($platform);
        } else {
            $result = false;
        }

        if (!$result) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[4010],
                    ])->withStatus(403);
        }

        $this->log($platform, $request);

        return $next($request, $response);
    }

    private function checkPlatformValue($platform)
    {
        $allowPlatform = ['Web', 'iOS', 'Android'];
        if (!in_array($platform, $allowPlatform)) {
            return false;
        }

        return true;
    }

    private function log($platform, $request)
    {
        $route = $request->getAttribute('route');

        /* @var $routelog \PP\Portal\dbModel\RouteLog */
        $routelog = new \PP\Portal\DbModel\RouteLog();
        $routelog->platform = $platform;
        $routelog->name = $route->getName();
        $routelog->url = $request->getUri();
        $routelog->post_data = json_encode($request->getParsedBody());
        $routelog->methods = json_encode($route->getMethods());
        $routelog->arguments = json_encode($route->getArguments());
        $routelog->save();
    }
}
