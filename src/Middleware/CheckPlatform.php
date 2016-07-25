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
