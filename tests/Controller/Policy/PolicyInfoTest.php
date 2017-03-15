<?php

namespace PP\Test\Policy;

class PolicyInfoTest extends \PHPUnit\Framework\TestCase
{
    protected $action;
    protected $response;

    protected function setUp()
    {
        $c = new \Slim\Container();
        $c['msgCode'] = function (\Slim\Container $c) {
            return [
                '3030' => [
                    'code'  => 3030,
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
        $c['UserPolicyModule'] = function ($c) {
            return new \PP\Portal\Module\UserPolicyModule($c);
        };
        $c['UserPolicyModule']->getUerPolicy(1);

        $this->action = new \PP\Portal\Controller\Policy\PolicyInfo($c);
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
        $this->assertEquals(3030, $out['status_code']);
        $this->assertArrayHasKey('ppmid', $out['data']);
        $this->assertArrayHasKey('policy_id', $out['data']);
        $this->assertArrayHasKey('premium_paid', $out['data']);
        $this->assertArrayHasKey('policy', $out['data']);

        $this->assertArrayHasKey('policy_id', $out['data']['policy']);
        $this->assertArrayHasKey('insurer', $out['data']['policy']);
        $this->assertArrayHasKey('plan_id', $out['data']['policy']);
        $this->assertArrayHasKey('deductible', $out['data']['policy']);
        $this->assertArrayHasKey('cover', $out['data']['policy']);
        $this->assertArrayHasKey('options', $out['data']['policy']);
        $this->assertArrayHasKey('medical_currency', $out['data']['policy']);
        $this->assertArrayHasKey('payment_frequency', $out['data']['policy']);
        $this->assertArrayHasKey('payment_method', $out['data']['policy']);
        $this->assertArrayHasKey('start_date', $out['data']['policy']);
        $this->assertArrayHasKey('end_date', $out['data']['policy']);
        $this->assertArrayHasKey('responsibility_id', $out['data']['policy']);
        $this->assertArrayHasKey('status', $out['data']['policy']);
        $this->assertArrayHasKey('Policy_Number', $out['data']['policy']);
        $this->assertArrayHasKey('renew_date', $out['data']['policy']);
        $this->assertArrayHasKey('is_end', $out['data']['policy']);
        $this->assertArrayHasKey('advisor', $out['data']['policy']);

        $this->assertArrayHasKey('responsibility_id', $out['data']['policy']['advisor']);
        $this->assertArrayHasKey('name', $out['data']['policy']['advisor']);
        $this->assertArrayHasKey('email', $out['data']['policy']['advisor']);
        $this->assertArrayHasKey('office_phone', $out['data']['policy']['advisor']);
        $this->assertArrayHasKey('contact_details_id', $out['data']['policy']['advisor']);
    }
}
