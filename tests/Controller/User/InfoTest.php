<?php

namespace PP\Test\User;

class InfoTest extends \PHPUnit_Framework_TestCase
{
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '2021' => [
                    'code'  => 2021,
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
        
        $this->action = new \PP\Portal\Controller\User\Info($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testValidate()
    {
        $action = $this->action;
        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2021, $out['status_code']);
        $this->assertArrayHasKey('ppmid', $out['data']);
        $this->assertArrayHasKey('title', $out['data']);
        $this->assertArrayHasKey('user_name', $out['data']);
    }

}
