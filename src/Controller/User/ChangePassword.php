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

        if ($this->checkRequiredData($data)) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => [
                'title' => 'Missing field(s)',
            ]]);
        }

        if ($this->c['UserModule']->isUserExistByID($args['id'])) {
            //todo check old password is same as now

            //check new password strength
            return $this->c['ViewHelper']->toJson($response, $this->passwordstrengthOutput($data));
        }

        return $this->c['ViewHelper']->toJson($response, ['errors' => [
            'title' => 'User Not Found',
        ]]);
    }

    private function passwordstrengthOutput($data)
    {
        //check new password strength
        if ($this->checkPasswordstrength($data['new_password'])) {
            //todo save new password
            return ['data' => [
                'title' => true,
            ]];
        }

        return ['errors' => [
                'title' => 'Password not strong enough',
            ]];
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    private function checkRequiredData($data)
    {
        return !isset($data['old_password']) || !isset($data['new_password']);
    }

    /**
     * check passwrod strength.
     *
     * @param string $value
     *
     * @return bool
     */
    private function checkPasswordstrength($value)
    {
        /* @var $passwrodModule \PP\Module\PasswordModule */
        $passwrodModule = $this->c['PasswordModule'];

        return $passwrodModule->validateLength($value, 6) &&
                $passwrodModule->validateLetters($value) &&
                $passwrodModule->validateNumbers($value);
    }
}
