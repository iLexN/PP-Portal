<?php

namespace PP\Test\User;

class PeopleTest extends \PHPUnit\Framework\TestCase
{
    protected $action;
    protected $response;
    protected $c;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '2630' => [
                    'code'  => 2630,
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
        //$c['UserModule']->isUserExistByID(2);

        $this->c = $c;
        $this->action = new \PP\Portal\Controller\User\People($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testGet()
    {
        $this->c['UserModule']->isUserExistByID(2);

        $action = $this->action;
        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, ['id' => 2]);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2630, $out['status_code']);

        $this->assertArrayHasKey('ppmid', $out['data'][0]);
        $this->assertArrayHasKey('title', $out['data'][0]);
        $this->assertArrayHasKey('first_name', $out['data'][0]);
        $this->assertArrayHasKey('middle_name', $out['data'][0]);
        $this->assertArrayHasKey('last_name', $out['data'][0]);
    }

    public function testGet2()
    {
        $this->c['UserModule']->isUserExistByID(9677);

        $action = $this->action;
        $environment = \Slim\Http\Environment::mock();
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, ['id' => 9677]);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2630, $out['status_code']);

        $this->assertArrayHasKey('ppmid', $out['data'][0]);
        $this->assertArrayHasKey('title', $out['data'][0]);
        $this->assertArrayHasKey('first_name', $out['data'][0]);
        $this->assertArrayHasKey('middle_name', $out['data'][0]);
        $this->assertArrayHasKey('last_name', $out['data'][0]);
    }
}
