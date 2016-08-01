<?php

namespace PP\Test\User;

class SignupTest extends \PHPUnit_Framework_TestCase
{
    protected $action;
    protected $response;
    protected $c;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '2040' => [
                    'code'  => 2040,
                ],
                '1010' => [
                    'code'  => 1010,
                ],
                '2510' => [
                    'code'  => 2510,
                ],
                '2070' => [
                    'code'  => 2070,
                ],
                '2030' => [
                    'code'  => 2030,
                ],
            ];
        };
        $c['pool'] = function () {
            $settings = [
                'path' => __DIR__.'/../cache/data',
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

        $this->c = $c;
        $this->action = new \PP\Portal\Controller\User\Signup($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testIsRegister()
    {
        $action = $this->action;

        $this->c['UserModule']->isUserExistByID(2);

        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2040, $out['status_code']);
    }

    public function testValidator()
    {
        $action = $this->action;

        $this->c['UserModule']->isUserExistByID(135929);

        $_POST['user_name'] = '';
        $_POST['password'] = '';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(1010, $out['status_code']);
    }

    public function testIsStrongPassword()
    {
        $action = $this->action;

        $this->c['UserModule']->isUserExistByID(135929);

        $_POST['user_name'] = 'ssss';
        $_POST['password'] = 'a';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2510, $out['status_code']);
    }

    public function testIsUserNameExist()
    {
        $action = $this->action;

        $this->c['UserModule']->isUserExistByID(135929);

        $_POST['user_name'] = 'alex';
        $_POST['password'] = 'P123Aaa3ss';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2070, $out['status_code']);
    }

    public function testSaveUser()
    {
        $action = $this->action;

        $this->c['UserModule']->isUserExistByID(135929);

        $_POST['user_name'] = 'alex11';
        $_POST['password'] = 'P123Aaa3ss';
        $environment = \Slim\Http\Environment::mock([
                'REQUEST_METHOD'    => 'POST',
                'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
            ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2030, $out['status_code']);
    }
}
