<?php

namespace PP\Portal\Controller\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \League\Fractal\Resource\Collection;

class Login
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
     * Login Post action.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $data = $request->getParsedBody();
        $this->c['logger']->info('post data', $data);

        if (!isset($data['clientID'])) {
            return $this->c['view']->render($request, $response, $this->missField('clientID'));
        }

        if (!isset($data['password'])) {
            return $this->c['view']->render($request, $response, $this->missField('password'));
        }

        if ( $this->isUserExist($data)){
            return $this->c['view']->render($request, $response, $this->success() );
        }

        return $this->c['view']->render($request, $response, ['errors'=>[
            'title'=>'Login User Not Found'
        ]]);
        
    }

    /**
     * check email in db or not(already user?).
     *
     * @param array $data
     *
     * @return bool
     */
    private function isUserExist($data)
    {
        return $this->c['UserModule']->isUserExistByID($data['clientID']) &&
                $this->c['UserModule']->client->verifyPassword($data['password']);
    }

    private function success(){
        /* @var $user \PP\Portal\dbModel\Client */
        $client = $this->c['UserModule']->client;
        
        return ['data'=>[
                'id'=>$client->Client_NO
                ]];
    }
    

    private function missField($field)
    {
        return ['errors'=>[
                'title'=>'Missing ' . $field
            ]];
    }
}
