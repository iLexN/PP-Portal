<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class InfoUpdate extends AbstractContainer
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
        /* @var $client \PP\Portal\DbModel\Client */
        $client = $this->c['UserModule']->client;

        $v = new \Valitron\Validator((array) $request->getParsedBody());
        $v->rule('required', ['username','password']);

        if (!$v->validate()) {
            return $this->c['ViewHelper']->toJson($response, ['errors' => 
                $this->c['msgCode'][1010]
            ]);
        }
        $data = $v->data();
        if (!$this->c['PasswordModule']->isStrongPassword($data['new_password'])) {
            return ['errors' => 
                $this->c['msgCode'][2510]
            ];
        }

        //todo: same password and username
        //$client->update($v->data());

        return $this->c['ViewHelper']->toJson($response, ['data' => 
            $this->c['msgCode'][2030]
        ]);
    }
}
