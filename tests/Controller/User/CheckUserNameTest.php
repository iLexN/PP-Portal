<?php

namespace PP\Test\User;

class CheckUserNameTest extends \PHPUnit\Framework\TestCase
{
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '2060' => [
                    'code'  => 2060,
                ],
                '2070' => [
                    'code'  => 2070,
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
        $this->action = new \PP\Portal\Controller\User\CheckUserName($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testIsUserNameExist()
    {
        $action = $this->action;

        $_POST['name'] = 'alex';
        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;

        $response = $action($request, $response, ['user_name' => '1235d8dd']);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2060, $out['status_code']);
    }

    public function testIsUserNameExistFail()
    {
        $action = $this->action;

        $_POST['name'] = 'alex';
        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;

        $response = $action($request, $response, ['user_name' => 'alex']);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2070, $out['status_code']);
    }
}
