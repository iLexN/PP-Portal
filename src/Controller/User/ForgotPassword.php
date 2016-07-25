<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class ForgotPassword extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $v = new \Valitron\Validator((array) $request->getParsedBody());
        $v->rule('required', ['user_name']);

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1010],
            ]);
        }

        if ($this->UserModule->isUserExistByUsername($v->data()['user_name'])) {
            $this->sendForgotPasswordEmail();

            return $this->ViewHelper->toJson($response, ['data' => $this->msgCode[2540],
            ]);
        }

        return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2010],
        ]);
    }

    /**
     * send email.
     */
    private function sendForgotPasswordEmail()
    {
        /* @var $user \PP\Portal\DbModel\User */
        $user = $this->UserModule->user;

        /* @var $mail \PHPMailer */
        $mail = $this->mailer;
        $mail->setFrom('info@pacificprime.com', 'Pacific Prime');
        $mail->addAddress('alex@kwiksure.com', $user->first_name.' '.$user->last_name);
        $mail->Subject = 'forgotpassword';
        $mail->msgHTML($this->twigView->fetch('email/forgot-password.twig', [
                'User' => $user,
            ]));
        if (!$mail->send()) {
            $this->logger->error('forgot password mail send fail'.$mail->ErrorInfo);
        } else {
            $this->logger->info('forgot password mail send');
        }
    }
}
