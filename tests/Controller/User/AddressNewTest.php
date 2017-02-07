<?php

namespace PP\Test\User;

class AddressNewTest extends \PHPUnit_Framework_TestCase
{
    protected $c;
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '1020' => [
                    'code'  => 1020,
                ],
                '2610' => [
                    'code'  => 2610,
                ],
            ];
        };
        $c['pool'] = function () {
            $settings = [
                'path' => __DIR__.'/../../cache/data',
            ];
            $driver = new \Stash\Driver\FileSystem($settings);

            return new \Stash\Pool($driver);
        };
        $c['dataCacheConfig'] = ['expiresAfter' => 1];
        $c['jsonConfig'] = ['prettyPrint' => false];
        $c['ViewHelper'] = function (\Slim\Container $c) {
            return new \PP\Portal\Module\Helper\View($c);
        };
        $c['UserModule'] = function ($c) {
            return new \PP\Portal\Module\UserModule($c);
        };
        $c['AddressModule'] = function ($c) {
            return new \PP\Portal\Module\AddressModule($c);
        };

        $this->c = $c;
        $this->action = new \PP\Portal\Controller\User\AddressNew($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testFail()
    {
        $this->c['UserModule']->isUserExistByID(2);
        $action = $this->action;

        $_POST = [];
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(1020, $out['status_code']);
    }

    public function testSuccess()
    {
        $this->c['UserModule']->isUserExistByID(2);
        $action = $this->action;

        $_POST = [
            'nick_name'      => 'a',
            'address_line_2' => 'line3',
        ];
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2610, $out['status_code']);
    }
}
