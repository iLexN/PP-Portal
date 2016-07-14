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
        $v->rule('required', ['clientID']);

        if (!$v->validate()) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => 
                $this->c['msgCode'][1010]
            ]);
        }

        if ($this->c['UserModule']->isUserExistByID($v->data()['clientID'])) {
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
        /* @var $client \PP\Portal\dbModel\Client */
        $client = $this->c['UserModule']->client;

        /* @var $mail \PHPMailer */
        $mail = $this->c['mailer'];
        $mail->setFrom('info@pacificprime.com', 'Pacific Prime');
        $mail->addAddress('alex@kwiksure.com', $client->First_Name.' '.$client->Surname);
        $mail->Subject = 'Forgot password test';
        $mail->msgHTML($this->c['twigView']->fetch('email/forgot-password.twig', [
                'Client' => $client,
            ]));
        if (!$mail->send()) {
            $this->c->logger->error('forgot password mail send fail'.$mail->ErrorInfo);
        } else {
            $this->c->logger->info('forgot password mail send');
        }
    }
}
