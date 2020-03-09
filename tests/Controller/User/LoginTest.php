<?php

namespace PP\Test\User;

class LoginTest extends \PHPUnit\Framework\TestCase
{
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '2081' => [
                    'code'  => 2081,
                ],
                '2080' => [
                    'code'  => 2080,
                ],
                '1010' => [
                    'code'  => 1010,
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

        $this->action = new \PP\Portal\Controller\User\Login($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testUserFound()
    {
        $action = $this->action;

        $_POST['user_name'] = 'alex';
        $_POST['password'] = '123Psadfs';
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2081, $out['status_code']);
    }

    public function testUserFoundwithWrongPassword()
    {
        $action = $this->action;

        $_POST['user_name'] = 'alex';
        $_POST['password'] = '123Psadfdds';
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2080, $out['status_code']);
    }

    public function testUserNotFound()
    {
        $action = $this->action;

        $_POST['user_name'] = 'alex8d8d8d8d';
        $_POST['password'] = '123Psadfs';
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2010, $out['status_code']);
    }

    public function testMissFields()
    {
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
        $this->assertEquals(1010, $out['status_code']);
    }
}
