<?php

namespace PP\Test\User;

class BankAccActionNewTest extends \PHPUnit_Framework_TestCase
{
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '1010' => [
                    'code'  => 1010,
                ],
                '3610' => [
                    'code'  => 3610,
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
        $c['UserBankAccModule'] = function ($c) {
            return new \PP\Portal\Module\UserBankAccModule($c);
        };

        $this->action = new \PP\Portal\Controller\User\BankAccActionNew($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testValidator()
    {
        $action = $this->action;

        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;

        $response = $action($request, $response, ['id'=>2]);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(1010, $out['status_code']);
    }

    public function testSusccess()
    {
        $action = $this->action;

        $_POST['iban'] = '1sdfds';
        $_POST['bank_swift_code'] = '1sdfdsf';
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, ['id' => 2]);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(3610, $out['status_code']);
    }
}
