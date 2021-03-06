<?php

namespace PP\Test\User;

class BankAccActionUpdateTest extends \PHPUnit\Framework\TestCase
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
                '1510' => [
                    'code'  => 1510,
                ],
                '3611' => [
                    'code'  => 3611,
                ],
                '3610' => [
                    'code'  => 3610,
                ],
                '3613' => [
                    'code'  => 3613,
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
        $c['UserModule']->isUserExistByID(2);

        $this->action = new \PP\Portal\Controller\User\BankAccActionUpdate($c);
        $this->response = new \Slim\Http\Response();
    }

    /**
     * @expectedException \Slim\Exception\NotFoundException
     */
    public function testNotBelongTo()
    {
        $action = $this->action;

        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;

        $response = $action($request, $response, ['acid' => 111, 'mode' => 'update']);
    }

    public function testValidate()
    {
        $action = $this->action;

        $_POST['iban'] = '1sdfds';
        //$_POST['bank_swift_code'] = '1sdfdsf';
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, ['acid' => 2, 'mode' => 'update']);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(1010, $out['status_code']);
    }

    public function testSuccess()
    {
        $action = $this->action;

        $_POST['nick_name'] = 'update';
        $_POST['account_user_name'] = 'update';
        $_POST['currency'] = 'USD';
        $_POST['account_number'] = 'nick';
        $_POST['bank_name'] = 'nick';

        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, ['acid' => 2, 'mode' => 'update']);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(3611, $out['status_code']);
    }

    public function testSuccessCreate()
    {
        $action = $this->action;

        $_POST['nick_name'] = 'update11';
        $_POST['account_user_name'] = 'update';
        $_POST['currency'] = 'USD';
        $_POST['account_number'] = 'nick';
        $_POST['bank_name'] = 'nick';

        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, ['id' => 2, 'mode' => 'create']);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(3610, $out['status_code']);
    }

    public function testNickNameUsedCreate()
    {
        $action = $this->action;

        $_POST['iban'] = 'update';
        $_POST['bank_swift_code'] = 'update';
        $_POST['account_user_name'] = 'update';
        $_POST['currency'] = 'USD';
        $_POST['account_number'] = 'update';
        $_POST['bank_name'] = 'update';
        $_POST['nick_name'] = 'update';

        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, ['id' => 2, 'mode' => 'create']);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(3613, $out['status_code']);
    }

    public function testNickNameUsedUpdate()
    {
        $action = $this->action;

        $_POST['iban'] = 'update';
        $_POST['bank_swift_code'] = 'update';
        $_POST['account_user_name'] = 'update';
        $_POST['currency'] = 'USD';
        $_POST['account_number'] = 'update';
        $_POST['bank_name'] = 'update';
        $_POST['nick_name'] = 'new nick';
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, ['acid' => 3, 'mode' => 'update']);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(3611, $out['status_code']);
    }

    public function testNickNameUsedUpdate2()
    {
        $action = $this->action;

        $_POST['iban'] = 'update';
        $_POST['bank_swift_code'] = 'update';
        $_POST['account_user_name'] = 'update';
        $_POST['currency'] = 'USD';
        $_POST['account_number'] = 'update';
        $_POST['bank_name'] = 'update';
        $_POST['nick_name'] = 'update';

        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, ['acid' => 2, 'mode' => 'update']);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(3611, $out['status_code']);
    }
}
