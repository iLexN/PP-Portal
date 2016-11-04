<?php

namespace PP\Test\Holder;

class InfoTest extends \PHPUnit_Framework_TestCase
{
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '2640' => [
                    'code'  => 2640,
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

        $this->action = new \PP\Portal\Controller\Holder\Info($c);
        $this->response = new \Slim\Http\Response();
    }

    /**
     * @expectedException \Slim\Exception\NotFoundException
     */
    public function testNotFound()
    {
        $action = $this->action;
        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, ['id' => 10000]);
    }

    public function testGet()
    {
        $action = $this->action;
        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, ['id' => 1]);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2640, $out['status_code']);
        $this->assertArrayHasKey('id', $out['data']);

        $this->assertArrayHasKey('policy_address_line_2', $out['data']);
        $this->assertArrayHasKey('policy_address_line_3', $out['data']);
        $this->assertArrayHasKey('policy_address_line_4', $out['data']);
        $this->assertArrayHasKey('policy_address_line_5', $out['data']);

        $this->assertArrayHasKey('mail_address_line_2', $out['data']);
        $this->assertArrayHasKey('mail_address_line_3', $out['data']);
        $this->assertArrayHasKey('mail_address_line_4', $out['data']);
        $this->assertArrayHasKey('mail_address_line_5', $out['data']);
    }
}
