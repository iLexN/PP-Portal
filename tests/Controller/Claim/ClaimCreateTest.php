<?php

namespace PP\Test\Claim;

class ClaimCreateTest extends \PHPUnit_Framework_TestCase
{
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
                '5010' => [
                    'code'  => 5010,
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

        $c['ClaimModule'] = function ($c) {
            return new \PP\Portal\Module\ClaimModule($c);
        };

        $this->action = new \PP\Portal\Controller\Claim\ClaimCreate($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testInValid()
    {
        $action = $this->action;

        $_POST['bank'] = [];
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;
        $response = $action($request, $response, ['id' => 1]);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(1020, $out['status_code']);
    }

    public function testSuccess()
    {
        $action = $this->action;

        $_POST['status'] = 'Save';
        $_POST['bank'] = ['iban' => '123', 'bank_swift_code' => 'sdfds'];
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;
        $response = $action($request, $response, ['id' => 1]);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(5010, $out['status_code']);
    }
}
