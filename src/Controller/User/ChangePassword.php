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
            return $this->c['ViewHelper']->toJson($response, ['errors' => 
                $this->c['msgCode'][1010]
            ]);
        }

        //todo check old password is same as now

        //check new password strength
        return $this->c['ViewHelper']->toJson($response, $this->passwordstrengthOutput($v->data()));
    }

    private function passwordstrengthOutput($data)
    {
        //check new password strength
        if ($this->c['PasswordModule']->isStrongPassword($data['new_password'])) {
            //todo save new password
            return ['data' => 
                $this->c['msgCode'][2530]
            ];
        }

        return ['errors' => 
                $this->c['msgCode'][2510]
            ];
    }
}
