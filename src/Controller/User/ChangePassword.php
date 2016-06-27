<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ChangePassword extends AbstractContainer
{
    /**
     * change password.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $v = new \Valitron\Validator((array) $request->getParsedBody());
        $v->rule('required', ['old_password', 'new_password']);

        if (!$v->validate()) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => [
                'title' => 'Missing field(s)',
            ]]);
        }

        //todo check old password is same as now

        //check new password strength
        return $this->c['ViewHelper']->toJson($response, $this->passwordstrengthOutput($v->data()));
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
