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
    
    public function __construct(\Slim\Container $container ,$username, $password, $realm = 'Protected Area')
    {
        $this->c = $container;
        $this->username = $username;
        $this->password = $password;
        $this->realm = $realm;
    }
    
    public function __invoke($request, $response, $next)
    {
        $authUser = $request->getHeaderLine('PHP_AUTH_USER');
        $authPass = $request->getHeaderLine('PHP_AUTH_PW');

        if ($authUser && $authPass && $authUser === $this->username && $authPass === $this->password) {
            return $next($request, $response);
        } else {
            $out = [
                'error'=>[
                    'status'=>401,
                    'title'=>'Need Authenticate'
                ]
            ];
            return $response->withHeader('WWW-Authenticate',sprintf('Basic realm="%s"', $this->realm))
                    ->withStatus(401)
                    ->write(json_encode($out));
        }
        
    }
    
}