<?php

namespace PP\Portal\Controller\User;

use PP\Portal\AbstractClass\AbstractContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class InfoUpdate extends AbstractContainer
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
        /* @var $client \PP\Portal\dbModel\Client */
        $client = $this->c['UserModule']->client;

        $v = new \Valitron\Validator((array) $request->getParsedBody(),[
            'Home_Address_1','Home_Address_2',
            'Home_Address_3','Home_Address_4','Home_Address_5',
        ]);

        try {
            $client->update($v->data());
            //$client->save();
            return $this->c['ViewHelper']->toJson($response, ['data' => [
                'title' => 'User Info Updated',
            ]]);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->c['logger']->error('sql error InfoUpdate', $e->errorInfo);

            return $this->c['ViewHelper']->toJson($response, ['errors' => [
                'title' => 'Field(s) not match',
            ]]);
        }
    }
}
