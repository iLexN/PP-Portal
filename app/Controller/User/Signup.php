<?php

namespace PP\Portal\Controller\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \League\Fractal\Resource\Collection;

class Signup
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

        if (!isset($data['email'])) {
            return $this->c['view']->render($request, $response, $this->missField('email'));
        }
        
        if (!isset($data['password'])) {
            return $this->c['view']->render($request, $response, $this->missField('password'));
        }

        //todo check exist client
        if ($this->isExistClient($data['email'])) {
            //create User
            $user = $this->c['UserModule']->create($data);

            $resource = new Collection($user, function ($user) {
                return [
                        'uid' => $user->id,
                        'token' => $user->token,
                        'tokenExpireDatetime'=>$user->tokenExpireDatetime,
                    ];
            });
            return $this->c['view']->render($request, $response, $this->c['dataManager']->createData($resource)->toArray());
        }
        
        return $this->c['view']->render($request, $response, ['errors'=>[
            'title'=>'Not Exist Client'
        ]]);
    }

    private function isExistClient($email)
    {
        //todo check existClient
        return true;
    }

    private function missField($field)
    {
        return ['errors'=>[
                'title'=>'Missing ' . $field
            ]];
    }
}
