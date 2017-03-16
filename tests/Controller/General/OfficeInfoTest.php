<?php

namespace PP\Test\General;

class OfficeInfoTest extends \PHPUnit\Framework\TestCase
{
    protected $action;
    protected $response;
    protected $request;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '1540' => [
                    'code'  => 1540,
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

        $this->action = new \PP\Portal\Controller\General\OfficeInfo($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testOfficeFound()
    {
        $action = $this->action;
        $request = $this->request;
        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(1540, $out['status_code']);

        $this->assertArrayHasKey('contact_details_id', $out['data'][0]);
        $this->assertArrayHasKey('region', $out['data'][0]);
        $this->assertArrayHasKey('region_full', $out['data'][0]);
        $this->assertArrayHasKey('tel_1', $out['data'][0]);
        $this->assertArrayHasKey('tel_2', $out['data'][0]);
        $this->assertArrayHasKey('fax_1', $out['data'][0]);
        $this->assertArrayHasKey('fax_2', $out['data'][0]);
        $this->assertArrayHasKey('fax_3', $out['data'][0]);
        $this->assertArrayHasKey('address_1', $out['data'][0]);
        $this->assertArrayHasKey('address_2', $out['data'][0]);
        $this->assertArrayHasKey('address_3', $out['data'][0]);
        $this->assertArrayHasKey('address_4', $out['data'][0]);
        $this->assertArrayHasKey('address_5', $out['data'][0]);
        $this->assertArrayHasKey('gmap_lat', $out['data'][0]);
        $this->assertArrayHasKey('gmap_lng', $out['data'][0]);
    }
}
