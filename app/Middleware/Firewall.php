<?php

namespace PP\Middleware;

/**
 * Description of Firewall
 *
 * @author user
 */
class Firewall {
    
    /**
     * @var \Slim\Container
     */
    protected $c;

    public function __construct(\Slim\Container $container)
    {
        $this->c = $container;
    }

    /**
     * add default Cache-Control no cache
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $clientIP = $this->getClientIP($request);
        $allowIP = $this->c->get('firewallConfig')['allow'];

        if ( !in_array($clientIP, $allowIP)) {
            return $this->c['view']->render($request, $response, [
                'error'=>[
                    'status'=>403,
                    'title'=>'Clinet IP not allow'
                ]
            ])->withStatus(403);
        }

        return $next($request, $response);
    }

    private function getClientIP($request){
        $serverParams = $request->getServerParams();
        if (isset($serverParams['REMOTE_ADDR'])) {
            return  $serverParams['REMOTE_ADDR'];
        }
        
        return '';
    }
}
