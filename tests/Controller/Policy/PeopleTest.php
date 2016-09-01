<?php

namespace PP\Test\Policy;

class PeopleTest extends \PHPUnit_Framework_TestCase
{
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '3040' => [
                    'code'  => 3040,
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

        $this->action = new \PP\Portal\Controller\Policy\People($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testInfo()
    {
        $action = $this->action;
        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, ['id'=>1]);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(3040, $out['status_code']);
        $this->assertArrayHasKey('ppmid', $out['data'][0]);
        $this->assertArrayHasKey('title', $out['data'][0]);
        $this->assertArrayHasKey('first_name', $out['data'][0]);
        $this->assertArrayHasKey('middle_name', $out['data'][0]);
        $this->assertArrayHasKey('last_name', $out['data'][0]);
    }
}
