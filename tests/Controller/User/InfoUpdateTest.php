<?php

namespace PP\Test\User;

class InfoUpdateTest extends \PHPUnit\Framework\TestCase
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
                '2020' => [
                    'code'  => 2020,
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

        $this->c = $c;
        $this->action = new \PP\Portal\Controller\User\InfoUpdate($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testValidate()
    {
        $this->c['UserModule']->isUserExistByID(2);
        $action = $this->action;

        $_POST['email'] = 'alex';
        $_POST['date_of_birth'] = '123Psadfs';
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

    public function testSave()
    {
        $this->c['UserModule']->isUserExistByID(173803);
        $action = $this->action;

        $_POST['email'] = 'check@check.com';
        $_POST['date_of_birth'] = '2010-10-10';
        $environment = \Slim\Http\Environment::mock([
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'multipart/form-data;',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);
        unset($_POST);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2020, $out['status_code']);
    }
}
