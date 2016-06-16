<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ForgotPassword extends AbstractContainer
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
        // id - clientID

        if (!isset($data['clientID'])) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => [
                'title' => 'Missing field(s)',
            ]]);
        }

        if ($this->c['UserModule']->isUserExistByID($data['clientID'])) {
            $this->sendForgotPasswordEmail();

            return $this->c['ViewHelper']->toJson($response, ['data' => [
                'title' => true,
            ]]);
        }

        return $this->c['ViewHelper']->toJson($response, ['errors' => [
            'title' => 'Login User Not Found',
        ]]);
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
