<?php

namespace PP\Portal\Middleware;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * CheckPlatform header PP-Portal-Platform.
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

/*
    private function sendGaEvent()
    {
        $httpClient = $this->c['httpClient'];
        $response = $httpClient->post('https://www.google-analytics.com/debug/collect', [
            'body' => 'v=1&t=event&tid=UA-5195172-41&cid=e4c2eec3-3834-4992-81ef-1e615c0e7177&ec=API&ea=Call&el=%7B%7BrouteName%7D%7D',
         ]);
        $log = [
                'getStatusCode' => $response->getStatusCode(),
                'body'          => (string) $response->getBody(),
            ];
        $this->c->logger->info('post file response', $log);
    }*/
}
