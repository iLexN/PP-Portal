<?php

namespace PP\Test\User;

class ChangePasswordTest extends \PHPUnit_Framework_TestCase
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
                '2520' => [
                    'code'  => 2520,
                ],
                '2530' => [
                    'code'  => 2530,
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
        $c['UserModule']->isUserExistByID(2);
        $c['PasswordModule'] = function ($c) {
            return new \PP\Portal\Module\PasswordModule($c);
        };
        $this->action = new \PP\Portal\Controller\User\ChangePassword($c);
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

        $_POST['new_password'] = '123';
        $_POST['old_password'] = '123';
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

    public function testPasswordVerify()
    {
        $action = $this->action;

        $_POST['new_password'] = '123aaaP22';
        $_POST['old_password'] = '123aaaP22';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2520, $out['status_code']);
    }

    public function testSave()
    {
        $action = $this->action;

        $_POST['new_password'] = '123Psadfs';
        $_POST['old_password'] = '123Psadfs';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2530, $out['status_code']);
    }

}
