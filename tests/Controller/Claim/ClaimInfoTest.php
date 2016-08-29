<?php

namespace PP\Test\Claim;

class ClaimInfoTest extends \PHPUnit_Framework_TestCase
{
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '6030' => [
                    'code'  => 6030,
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
        $c['ClaimModule']->geInfoById(1);

        $this->action = new \PP\Portal\Controller\Claim\ClaimInfo($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testSuccess()
    {
        $action = $this->action;

        $_POST['bank'] = [];
        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(6030, $out['status_code']);
        $this->assertArrayHasKey('claim_id', $out['data']);
        $this->assertEquals(1, $out['data']['user_policy_id']);
        $this->assertArrayHasKey('file_attachments', $out['data']);
        $this->assertArrayHasKey('bank_info', $out['data']);
    }
}
