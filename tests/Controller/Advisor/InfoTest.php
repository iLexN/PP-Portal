<?php

namespace PP\Test\Advisor;

class InfoTest extends \PHPUnit\Framework\TestCase
{
    protected $action;
    protected $response;
    protected $request;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '5040' => [
                    'code'  => 5040,
                ],
                '3510' => [
                    'code'  => 3510,
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

        $c['jsonConfig'] = ['prettyPrint' => false];
        $c['ViewHelper'] = function (\Slim\Container $c) {
            return new \PP\Portal\Module\Helper\View($c);
        };

        $environment = \Slim\Http\Environment::mock([]);
        $this->request = \Slim\Http\Request::createFromEnvironment($environment);

        $this->action = new \PP\Portal\Controller\Advisor\Info($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testAdvisorFound()
    {
        $action = $this->action;
        $request = $this->request;
        $response = $this->response;

        $response = $action($request, $response, ['id' => 19]);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(5040, $out['status_code']);
        $this->assertArrayHasKey('responsibility_id', $out['data']);
        $this->assertArrayHasKey('name', $out['data']);
        $this->assertArrayHasKey('email', $out['data']);
        $this->assertArrayHasKey('office_phone', $out['data']);
        $this->assertArrayHasKey('contact_details_id', $out['data']);
    }

    public function testAdvisorNotFound()
    {
        $action = $this->action;
        $request = $this->request;
        $response = $this->response;

        $response = $action($request, $response, ['id' => 1]);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(3510, $out['status_code']);
    }
}
