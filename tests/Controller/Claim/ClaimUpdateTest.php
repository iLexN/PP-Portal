<?php

namespace PP\Test\Claim;

class ClaimUpdateTest extends \PHPUnit\Framework\TestCase
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
                '6020' => [
                    'code'  => 6020,
                ],
                '6021' => [
                    'code'  => 6021,
                ],
                '5010' => [
                    'code'  => 5010,
                ],
                '5011' => [
                    'code'  => 5011,
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

        $this->c = $c;
        $this->action = new \PP\Portal\Controller\Claim\ClaimUpdate($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testInValid()
    {
        $this->c['ClaimModule']->geInfoById(2);
        $action = $this->action;

        $_POST['bank'] = [];
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;
        $response = $action($request, $response, ['id' => 1, 'mode' => 'create']);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(1020, $out['status_code']);
    }

    public function testSuccess()
    {
        $this->c['ClaimModule']->geInfoById(2);
        $action = $this->action;

        $_POST['status'] = 'Submit';
        $_POST['claimiant_ppmid'] = 2;
        $_POST['bank'] = ['account_user_name' => '123', 'currency' => 'USD', 'account_number'=> '112', 'bank_name'=>'123'];
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;
        $response = $action($request, $response, ['id' => 1, 'mode' => 'update']);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(6020, $out['status_code']);
    }

    public function testSuccess2()
    {
        $this->c['ClaimModule']->geInfoById(1);
        $action = $this->action;

        $_POST['status'] = 'Save';
        $_POST['bank'] = ['account_user_name' => '123', 'currency' => 'USD', 'account_number'=> '112', 'bankname'=>123];
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;
        $response = $action($request, $response, ['id' => 1, 'mode' => 'update']);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(6021, $out['status_code']);
    }

    public function testCreateSubmit()
    {
        $this->c['ClaimModule']->geInfoById(1);
        $action = $this->action;

        $_POST['status'] = 'Submit';
        $_POST['claimiant_ppmid'] = 2;
        $_POST['bank'] = ['account_user_name' => '123', 'currency' => 'USD', 'account_number'=> '112', 'bank_name'=>'11'];
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;
        $response = $action($request, $response, ['id' => 1, 'mode' => 'create']);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(5010, $out['status_code']);
    }

    public function testCreateSave()
    {
        $this->c['ClaimModule']->geInfoById(1);
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
        $response = $action($request, $response, ['id' => 1, 'mode' => 'create']);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(5011, $out['status_code']);
    }
}
