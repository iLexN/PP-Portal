<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ChangePassword extends AbstractContainer
{
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
        
        $v = new \Valitron\Validator($data);
        $v->rule('required', ['old_password', 'new_password']);

        if(!$v->validate()) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => [
                'title' => 'Missing field(s)',
            ]]);
        }

        //todo check old password is same as now

        //check new password strength
        return $this->c['ViewHelper']->toJson($response, $this->passwordstrengthOutput($data));
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
