<?php

namespace PP\Test\User;

class ForgotPasswordTokenUpdateTest extends \PHPUnit_Framework_TestCase
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
                '2510' => [
                    'code'  => 2510,
                ],
                '2570' => [
                    'code'  => 2570,
                ],
                '2010' => [
                    'code'  => 2010,
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
        $c['PasswordModule'] = function ($c) {
            return new \PP\Portal\Module\PasswordModule($c);
        };

        $this->action = new \PP\Portal\Controller\User\ForgotPasswordTokenUpdate($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testValidator()
    {
        $action = $this->action;

        $_POST['name'] = 'alex';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(1010, $out['status_code']);
    }

    public function testIsStrongPassword()
    {
        $action = $this->action;

        $_POST['new_password'] = '1';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2510, $out['status_code']);
    }

    public function testIsUserExistByForgotToken()
    {
        $action = $this->action;

        $_POST['new_password'] = '123Psadfs';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, ['token' => '5550522d-f8a8-4203-81c1-fa3b567157cc']);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2570, $out['status_code']);
    }

    public function testIsUserExistByForgotTokenFail()
    {
        $action = $this->action;

        $_POST['new_password'] = 'alex123AA';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, ['token' => 'ddd']);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2010, $out['status_code']);
    }
}
