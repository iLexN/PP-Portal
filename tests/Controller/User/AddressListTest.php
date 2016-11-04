<?php

namespace PP\Test\User;

class AddressListTest extends \PHPUnit_Framework_TestCase
{
    protected $c;
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '2600' => [
                    'code'  => 2600,
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
        $this->action = new \PP\Portal\Controller\User\AddressList($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testGet()
    {
        $this->c['UserModule']->isUserExistByID(2);
        $action = $this->action;
        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2600, $out['status_code']);

        $this->assertArrayHasKey('id', $out['data'][0]);
        $this->assertArrayHasKey('nick_name', $out['data'][0]);
        $this->assertArrayHasKey('address_line_2', $out['data'][0]);
        $this->assertArrayHasKey('address_line_3', $out['data'][0]);
        $this->assertArrayHasKey('address_line_4', $out['data'][0]);
        $this->assertArrayHasKey('address_line_5', $out['data'][0]);
        $this->assertArrayHasKey('status', $out['data'][0]);
        $this->assertArrayHasKey('ppmid', $out['data'][0]);
        $this->assertArrayHasKey('old_id', $out['data'][0]);
        $this->assertArrayHasKey('created_at', $out['data'][0]);
        $this->assertArrayHasKey('updated_at', $out['data'][0]);
    }
}
