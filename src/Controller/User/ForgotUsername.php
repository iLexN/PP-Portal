<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class ForgotUsername extends AbstractContainer
{
    public function __invoke(ServerRequestInterface $request, Response $response, array $args)
    {
        $user = $this->UserModule->newForgotUsername();

        $v = new \Valitron\Validator((array) $request->getParsedBody(), $user->getFillable());
        $v->rule('required', ['name', 'email', 'phone']);
        $v->rule('email', 'email');

        if (!$v->validate()) {
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[1020]]);
        }

        $data = $v->data();
        if (!$this->userMatch($data)) {
            $this->UserModule->saveForgotUsername($user, $data);
            return $this->ViewHelper->toJson($response, ['errors' => $this->msgCode[2010]]);
        }

        // success send email not need log to db
        //$this->UserModule->saveForgotUsername($user, $data);
        $this->sendForgotUsernameEmail();

        return $this->ViewHelper->toJson($response, ['data' => $this->msgCode[2550],
        ]);
    }

    private function userMatch($data)
    {
        if (!$this->UserModule->isUserExistByEmail($data['email'])) {
            return false;
        }
        if (!$this->UserModule->isPhoneMatch($data['phone'])) {
            return false;
        }

        return true;
    }

    /**
     * send email.
     */
    private function sendForgotUsernameEmail()
    {
        /* @var $user \PP\Portal\DbModel\User */
        $user = $this->UserModule->user;

        /* @var $mail \PHPMailer */
        $mail = $this->mailer;
        $mail->setFrom($this->c->get('mailConfig')['fromAc'], $this->c->get('mailConfig')['fromName']);
        $mail->addAddress($user->email, $user->first_name.' '.$user->last_name);
        $mail->Subject = 'Forgot Username success';
        $mail->msgHTML($this->twigView->fetch('email/forgot-username.twig', [
                'User' => $user,
            ]));
        if (!$mail->send()) {
            $this->c->logger->error('forgot password mail send fail'.$mail->ErrorInfo);
        }
    }
}
