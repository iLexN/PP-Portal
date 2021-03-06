<?php

namespace PP\Test\Policy;

class PolicyListTest extends \PHPUnit\Framework\TestCase
{
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '3020' => [
                    'code'  => 3020,
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
        $c['UserPolicyModule'] = function ($c) {
            return new \PP\Portal\Module\UserPolicyModule($c);
        };

        $this->action = new \PP\Portal\Controller\Policy\PolicyList($c);
        $this->response = new \Slim\Http\Response();
    }

    public function testInfo()
    {
        $action = $this->action;
        $environment = \Slim\Http\Environment::mock([]);
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        $response = $this->response;
        $response = $action($request, $response, []);

        $out = json_decode((string) $response->getBody(), true);
        $this->assertEquals(3020, $out['status_code']);
        $this->assertArrayHasKey('plan_id', $out['data'][0]);
        $this->assertArrayHasKey('insurer', $out['data'][0]);
        $this->assertArrayHasKey('plan_id', $out['data'][0]);
        $this->assertArrayHasKey('deductible', $out['data'][0]);
        $this->assertArrayHasKey('cover', $out['data'][0]);
        $this->assertArrayHasKey('options', $out['data'][0]);
        $this->assertArrayHasKey('medical_currency', $out['data'][0]);
        $this->assertArrayHasKey('payment_frequency', $out['data'][0]);
        $this->assertArrayHasKey('payment_method', $out['data'][0]);
        $this->assertArrayHasKey('start_date', $out['data'][0]);
        $this->assertArrayHasKey('responsibility_id', $out['data'][0]);
        $this->assertArrayHasKey('status', $out['data'][0]);
        $this->assertArrayHasKey('renew_date', $out['data'][0]);
        $this->assertArrayHasKey('pivot', $out['data'][0]);
        $this->assertArrayHasKey('advisor', $out['data'][0]);
    }
}
