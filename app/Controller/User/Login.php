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

        if (!isset($data['email'])) {
            return $this->c['view']->render($request, $response, $this->missField('email'));
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
        return $this->c['UserModule']->isUserExistByEmail($data['email']) &&
                $this->c['UserModule']->user->verifyPassword($data['password']);
    }

    private function success(){
        /* @var $user \PP\Portal\dbModel\User */
        $user = $this->c['UserModule']->user;
        $user->genToken();
        $user->save();

        $resource = new Collection($user, function ($user) {
            return [
                    'uid' => $user->id,
                    'token' => $user->token,
                    'tokenExpireDatetime'=>$user->tokenExpireDatetime,
                ];
        });

        return $this->c['dataManager']->createData($resource)->toArray();
    }
    

    private function missField($field)
    {
        return ['errors'=>[
                'title'=>'Missing ' . $field
            ]];
    }
}
