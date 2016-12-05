<?php

namespace PP\Test\Claim;

class ClaimListTest extends \PHPUnit_Framework_TestCase
{
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '5030' => [
                    'code'  => 5030,
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

        $c['ClaimModule'] = function ($c) {
            return new \PP\Portal\Module\ClaimModule($c);
        };
        $c['UserPolicyModule'] = function ($c) {
            return new \PP\Portal\Module\UserPolicyModule($c);
        };
        $c['UserPolicyModule']->getUerPolicy(1);

        $this->action = new \PP\Portal\Controller\Claim\ClaimList($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testSuccess()
    {
        $action = $this->action;

        $_POST['bank'] = [];
        $environment = \Slim\Http\Environment::mock([
            'QUERY_STRING' => 'status=All',
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(5030, $out['status_code']);
        $this->assertArrayHasKey('Save', $out['data']);
        $this->assertArrayHasKey('Submit', $out['data']);
    }

/*
    public function testSuccessWithStatus()
    {
        $action = $this->action;

        $_POST['bank'] = [];
        $environment = \Slim\Http\Environment::mock([
            'QUERY_STRING'=>'status=Save'
        ]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(5030, $out['status_code']);
    }*/
}
