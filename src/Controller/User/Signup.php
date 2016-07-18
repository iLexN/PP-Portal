<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Signup extends AbstractContainer
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        /* @var $user \PP\Portal\DbModel\User */
        $user = $this->c['UserModule']->user;

        if (!$user->isRegister()) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => $this->c['msgCode'][2040],
            ]);
        }

        $v = new \Valitron\Validator((array) $request->getParsedBody());
        $v->rule('required', ['user_name', 'password']);

        if (!$v->validate()) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => $this->c['msgCode'][1010],
            ]);
        }
        $data = $v->data();
        if (!$this->c['PasswordModule']->isStrongPassword($data['password'])) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => $this->c['msgCode'][2510],
            ]);
        }

        if (!$this->c['UserModule']->isUserNameExist($data['user_name'])) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => $this->c['msgCode'][2070],
            ]);
        }

        $user->user_name = $data['user_name'];
        $user->password = $this->c['PasswordModule']->passwordHash($data['password']);
        $user->save();

        $this->sendSignupEmail();

        return $this->c['ViewHelper']->toJson($response, ['data' => $this->c['msgCode'][2030],
        ]);
    }

    /**
     * send email.
     */
    private function sendSignupEmail()
    {
        /* @var $user \PP\Portal\DbModel\User */
        $user = $this->c['UserModule']->user;

        /* @var $mail \PHPMailer */
        $mail = $this->c['mailer'];
        $mail->setFrom('info@pacificprime.com', 'Pacific Prime');
        $mail->addAddress('alex@kwiksure.com', $user->first_name.' '.$user->last_name);
        $mail->Subject = 'Signup success';
        $mail->msgHTML($this->c['twigView']->fetch('email/signup.twig', [
                'User' => $user,
            ]));
        if (!$mail->send()) {
            $this->c->logger->error('forgot password mail send fail'.$mail->ErrorInfo);
        } else {
            $this->c->logger->info('forgot password mail send');
        }
    }
}
