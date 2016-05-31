<?php
namespace PP\Middleware;

/**
 * Description of HttpBasicAuthMiddleWare
 *
 * @author user
 */
class CheckToken {
    
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
        if ( !$request->hasHeader('PP-Portal-Token') ){
            return $this->c['view']->render($request, $response, ['errors'=>[
                        'status'=>401,
                        'title'=>'Need Authenticate'
                    ]])->withHeader('WWW-Authenticate','Basic realm="Protected Area"')
                    ->withStatus(401);
        }

        $token = $request->getHeaderLine('PP-Portal-Token');

        $this->c->logger->info('token',[$token]);

        /* @var $userModule \PP\Module\UserModule */
        $userModule = $this->c['UserModule'];
        if ( !$userModule->verifyToken($token) ) {
            return $this->c['view']->render($request, $response, ['errors'=>[
                        'status'=>401,
                        'title'=>'Need Authenticate'
                    ]])->withHeader('WWW-Authenticate','Basic realm="Protected Area"')
                    ->withStatus(401);
        }


        $this->c['user'] = $userModule->user;

        return $next($request, $response);
    }
    
}
