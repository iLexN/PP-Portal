<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Signup extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        /* @var $user \PP\Portal\DbModel\User */
        $user = $this->UserModule->user;

        if (!$user->isRegister()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2040],
            ]);
        }

        $v = new \Valitron\Validator((array) $request->getParsedBody());
        $v->rule('required', ['user_name', 'password']);

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1010],
            ]);
        }
        $data = $v->data();
        if (!$this->c['PasswordModule']->isStrongPassword($data['password'])) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => $this->c['msgCode'][2510],
            ]);
        }

        if (!$this->UserModule->isUserNameExist($data['user_name'])) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2070],
            ]);
        }

        //success action
        $this->UserModule->saveSignUp($data);
        $this->sendSignupEmail();

        return $this->ViewHelper->toJson($response, ['data' => $this->msgCode[2030],
        ]);
    }

    /**
     * send email.
     */
    private function sendSignupEmail()
    {
        /* @var $user \PP\Portal\DbModel\User */
        $user = $this->UserModule->user;

        /* @var $mail \PHPMailer */
        $mail = $this->mailer;
        $mail->setFrom('info@pacificprime.com', 'Pacific Prime');
        $mail->addAddress('alex@kwiksure.com', $user->first_name.' '.$user->last_name);
        $mail->Subject = 'Signup success';
        $mail->msgHTML($this->twigView->fetch('email/signup.twig', [
                'User' => $user,
            ]));
        if (!$mail->send()) {
            $this->c->logger->error('forgot password mail send fail'.$mail->ErrorInfo);
        } else {
            $this->c->logger->info('forgot password mail send');
        }
    }
}
