<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ForgotPassword extends AbstractContainer
{
    /**
     * forgot passwrod.
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
        $v->rule('required', ['user_name']);

        if (!$v->validate()) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => 
                $this->c['msgCode'][1010]
            ]);
        }

        if ($this->c['UserModule']->isUserExistByUsername($v->data()['user_name'])) {
            $this->sendForgotPasswordEmail();

            return $this->c['ViewHelper']->toJson($response, ['data' => 
                $this->c['msgCode'][2540]
            ]);
        }

        return $this->c['ViewHelper']->toJson($response, ['errors' => 
            $this->c['msgCode'][2010]
        ]);
    }

    /**
     * send email.
     */
    private function sendForgotPasswordEmail()
    {
        /* @var $user \PP\Portal\DbModel\User */
        $user = $this->c['UserModule']->user;

        /* @var $mail \PHPMailer */
        $mail = $this->c['mailer'];
        $mail->setFrom('info@pacificprime.com', 'Pacific Prime');
        $mail->addAddress('alex@kwiksure.com', $user->first_name.' '.$user->last_name);
        $mail->Subject = 'forgotpassword';
        $mail->msgHTML($this->c['twigView']->fetch('email/forgot-password.twig', [
                'User' => $user,
            ]));
        if (!$mail->send()) {
            $this->c->logger->error('forgot password mail send fail'.$mail->ErrorInfo);
        } else {
            $this->c->logger->info('forgot password mail send');
        }
    }
}
