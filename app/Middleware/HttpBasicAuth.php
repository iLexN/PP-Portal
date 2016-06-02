<?php
namespace PP\Middleware;

/**
 * Description of HttpBasicAuthMiddleWare
 *
 * @author user
 */
class HttpBasicAuth {
    
    /**
     * @var string
     */
    protected $realm;
    /**
     * @var string
     */
    protected $username;
    /**
     * @var string
     */
    protected $password;

    /**
     * @var \Slim\Container
     */
    protected $c;
    
    public function __construct(\Slim\Container $container , $realm = 'Protected Area')
    {
        $this->c = $container;
        $this->username = $container->get('firewallConfig')['username'];
        $this->password = $container->get('firewallConfig')['password'];
        $this->realm = $realm;
    }
    
    public function __invoke($request, $response, $next)
    {
        $this->c->logger->info('Middleware HttpBasicAuth');
        
        $authUser = $request->getHeaderLine('PHP_AUTH_USER');
        $authPass = $request->getHeaderLine('PHP_AUTH_PW');

        if ($authUser && $authPass && $authUser === $this->username && $authPass === $this->password) {
            return $next($request, $response);
        } else {
            return $this->c['view']->render($request, $response, ['errors'=>[
                        'status'=>401,
                        'title'=>'Need Authenticate'
                    ]])->withHeader('WWW-Authenticate',sprintf('Basic realm="%s"', $this->realm))
                    ->withStatus(401);
        }
        
    }
    
}
