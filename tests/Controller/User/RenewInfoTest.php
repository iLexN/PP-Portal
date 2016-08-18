<?php

namespace PP\Test\User;

class RenewInfoTest extends \PHPUnit_Framework_TestCase
{
    protected $c;
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '2022' => [
                    'code'  => 2022,
                ],
                '2023' => [
                    'code'  => 2023,
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
        $this->action = new \PP\Portal\Controller\User\RenewInfo($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testHaveInfo()
    {
        $this->c['UserModule']->isUserExistByID(2);

        $action = $this->action;

        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2022, $out['status_code']);
    }

    public function testwithoutInfo()
    {
        $this->c['UserModule']->isUserExistByID(9677);

        $action = $this->action;

        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;

        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(2023, $out['status_code']);
    }

}
