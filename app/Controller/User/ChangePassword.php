<?php

namespace PP\Portal\Controller\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ChangePassword
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
        $data = (array) $request->getParsedBody();
        // old_password , new_password

        $this->c['logger']->info('change password data', $data);

        if (!isset($data['old_password']) || !isset($data['new_password'])) {
            return $this->c['view']->render($request, $response, ['errors' => [
                'title' => 'Missing field(s)',
            ]]);
        }

        if ($this->isUserExist($args['id'])) {

            //todo check old password is same as now

            //check new password strength
            if ($this->checkPasswordstrength($data['new_password'])) {
                //todo save new password
                return $this->c['view']->render($request, $response, ['data' => [
                    'title' => true,
                ]]);
            }

            return $this->c['view']->render($request, $response, ['errors' => [
                'title' => 'Password not strong enough',
            ]]);
        }

        return $this->c['view']->render($request, $response, ['errors' => [
            'title' => 'User Not Found',
        ]]);
    }

    /**
     * check is user exist
     *
     * @param int $id
     *
     * @return bool
     */
    private function isUserExist($id)
    {
        return  $this->c['UserModule']->isUserExistByID($id)  ;
    }

    /**
     * check passwrod strength
     *
     * @param string $value
     * @return bool
     */
    private function checkPasswordstrength($value)
    {
        return $this->c['PasswordModule']->checkPasswordstrength($value, [
            'letters','numbers','length'
        ]);
    }
}
